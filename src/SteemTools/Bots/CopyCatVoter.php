<?php
namespace SteemTools\Bots;

//date_default_timezone_set('America/Chicago');
date_default_timezone_set('UTC');


class CopyCatVoter
{
    public $SteemAPI;
    public $last_vote = null;
    public $last_content = null;
    public $max_timestamp = 0;
    public $last_post_is_by_followed_author = false;

    // config params
    public $follow_vote_comments = false;
    public $looptime_in_seconds = 15;
    public $reminder_in_seconds = 60;
    public $auto_vote = false;
    public $delay_in_minutes_to_vote_post_by_followed_author = 30;

    public function __construct($SteemAPI, $config = array())
    {
        $this->SteemAPI = $SteemAPI;
        if (array_key_exists('follow_vote_comments', $config)) {
            $this->follow_vote_comments = $config['follow_vote_comments'];
        }
        if (array_key_exists('looptime_in_seconds', $config)) {
            $this->looptime_in_seconds = $config['looptime_in_seconds'];
        }
        if (array_key_exists('reminder_in_seconds', $config)) {
            $this->reminder_in_seconds = $config['reminder_in_seconds'];
        }
        if (array_key_exists('auto_vote', $config)) {
            $this->auto_vote = $config['auto_vote'];
        }
        if (array_key_exists('delay_in_minutes_to_vote_post_by_followed_author', $config)) {
            $this->delay_in_minutes_to_vote_post_by_followed_author = $config['delay_in_minutes_to_vote_post_by_followed_author'];
        }
    }

    public function hasNewVote($account)
    {
        $changed = false;
        $votes = $this->SteemAPI->getAccountVotes(array($account));
        $last_vote = null;
        foreach($votes as $vote) {
            $include_vote = true;
            if ($vote['percent'] != 10000 && $vote['percent'] != 0) {
                $include_vote = false;
            }
            if (!$this->follow_vote_comments && $this->isCommentVote($vote)) {
                $include_vote = false;
            }
            if ($include_vote) {
                $timestamp = strtotime($vote['time']);
                if ($this->max_timestamp < $timestamp) {
                    $last_vote = $vote;
                    $this->max_timestamp = $timestamp;
                }
            }
        }
        if ($last_vote) {
            if ($this->last_vote != $last_vote) {
                $changed = true;
                $this->last_vote = $last_vote;
                $this->updateContentOfLastVote();
            }
        }
        return $changed;
    }

    public function updateContentOfLastVote()
    {
        $author_and_link = $this->SteemAPI->getAuthorAndPermLink($this->last_vote['authorperm']);
        $this->last_content = $this->SteemAPI->getContent(
            array(
                $author_and_link['author'],
                $author_and_link['permlink']
                )
            );
    }

    public function isCommentVote($vote)
    {
        return (strpos($vote['authorperm'], '/re-') !== false);
    }

    public function hasVoted($account, $refresh_content = false)
    {
        if ($refresh_content) {
            $this->updateContentOfLastVote();
        }
        foreach ($this->last_content['active_votes'] as $active_vote) {
            if ($active_vote['voter'] == $account) {
                return true;
            }
        }
        return false;
    }

    public function run($voter_account, $account_to_copy)
    {
        print date('c') . "\n";
        print "Starting CopyCatVoter for $voter_account who wants to copy $account_to_copy...\n\n";
        $time = time();
        while(true) {
            if ($this->hasNewVote($account_to_copy)) {
                print "\n------------ NEW VOTE! -----------\n";
                print "https://steemit.com" . $this->last_content['url'] . "\n";
                print $this->last_vote['time'] . "\n";
                print "----------------------------------\n";
                if (!$this->hasVoted($voter_account)) {
                    if ($this->auto_vote) {
                        $weight = 100;
                        if ($this->last_vote['percent'] == 0) {
                            $weight = 0;
                            print "**** UNOVOTING ****\n";
                        } else {
                            print "Voting...\n";
                            if ($this->last_content['author'] == $account_to_copy && $this->last_content['depth'] == 0) {
                                print "Looks like this is a new root post by the account we're copying.\n";
                                print "Waiting " . $this->delay_in_minutes_to_vote_post_by_followed_author . " minutes to vote.\n";
                                for($i = 0; $i < $this->delay_in_minutes_to_vote_post_by_followed_author; $i++) {
                                    print "Voting in " . ($this->delay_in_minutes_to_vote_post_by_followed_author - $i) . " minutes...\n";
                                    sleep(60);
                                }
                            }
                        }
                        $this->vote($voter_account, $this->last_vote['authorperm'], $weight);
                    } else {
                        print "\n";
                        print "Go vote for https://steemit.com" . $this->last_content['url'] . "\n";
                        print "\n";
                    }
                } else {
                    print "Already voted.\n";
                }
            } else {
                if (!$this->auto_vote && (time() - $time) >= $this->reminder_in_seconds) {
                    if (!$this->hasVoted($voter_account,true)) {
                        print "\n";
                        print "REMINDER: Go vote for https://steemit.com" . $this->last_content['url'] . "\n";
                        print "\n";
                    }
                    $time = time();
                }
            }
            print '.';
            sleep($this->looptime_in_seconds);
        }
    }

    /*
     * This method uses the command line piston and must have a posting key with no wallet password.
     */
    public function vote($voter, $permlink, $weight = 100)
    {
        $command = 'piston upvote --voter ' . $voter;
        $command .= ' --weight ' . $weight;
        $command .= ' ' . $permlink;
        $output = array();
        $return = array();
        $result = exec($command, $output, $return);
        /*
        var_dump($output);
        var_dump($return);
        var_dump($result);
        */
        file_put_contents('copy_cat_voter_history.txt', '"' . date('c') . '","' . $permlink . "\"\n", FILE_APPEND);
    }

    public function currationReport($account)
    {
        $currations = array();
        $file = @fopen("copy_cat_voter_history.txt","r");
        $count = 0;
        if ($file) {
            while(!feof($file)) {
                $count++;
                $line = fgetcsv($file);
                $currations[trim($line[1])] = array('time' => trim($line[0]), 'permlink' => trim($line[1]), 'curate_reward' => 0.0);
            }
            fclose($file);
        }

        $start = date('c',strtotime('last week'));
        $end = date('c');
        $curration_rewards = $this->SteemAPI->getAccountHistoryFiltered($account, array('curate_reward'),$start,$end);
        $curration_rewards = $this->SteemAPI->getOpData($curration_rewards);

        foreach ($curration_rewards as $curration_reward) {
            $key = $curration_reward['comment_author'] . '/' . $curration_reward['comment_permlink'];
            if (array_key_exists($key, $currations)) {
                $currations[$key]['curate_reward'] = $this->SteemAPI->vest2sp(str_replace(' VESTS', '', $curration_reward['reward']));
            }
        }

        $total_sp = 0;
        $report_string = "Curation Reward (SP),permlink\n";
        foreach ($currations as $curration) {
            $line = $curration['curate_reward'] . ',' . $curration['permlink'] . "\n";
            $report_string .= $line;
            $total_sp += $curration['curate_reward'];
            print $line;
        }

        print "\n\nTotal SP Earned by CopCatVoter: " . $total_sp . "\n\n";

        $filename = 'copy_cat_voter_history_with_curation_rewards_' . date('Y-m-d') . '.txt';

        file_put_contents($filename, $report_string);

        print "File saved: " . $filename;
    }

}