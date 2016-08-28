<?php
namespace SteemTools\Bots;

//date_default_timezone_set('America/Chicago');
date_default_timezone_set('UTC');


class CopyCatVoter
{
    public $SteemAPI;
    public $last_vote = null;
    public $last_content_id = 0;
    public $last_content = null;
    public $max_timestamp = 0;

    // config params
    public $follow_vote_comments = false;
    public $looptime_in_seconds = 15;
    public $reminder_in_seconds = 60;
    public $auto_vote = false;

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
    }

    public function hasNewVote($account)
    {
        $changed = false;
        $votes = $this->SteemAPI->getAccountVotes(array($account));
        $last_vote = null;
        foreach($votes as $vote) {
            $include_vote = true;
            if ($vote['percent'] != 10000) {
                $include_vote = false;
            }
            if (!$this->follow_vote_comments && $this->isCommentVote($vote,$account)) {
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
        $author_and_link = $this->getAuthorAndPermLink($this->last_vote['authorperm']);
        $this->last_content = $this->SteemAPI->getContent(
            array(
                $author_and_link['author'],
                $author_and_link['permlink']
                )
            );
    }

    public function isCommentVote($vote, $account)
    {
        return (strpos($vote['authorperm'], $account . '/re-') === 0);
    }

    public function getAuthorAndPermLink($authorperm)
    {
        list($author,$permlink,$original) = explode('/',$authorperm, 3);
        return array('author' => $author, 'permlink' => $permlink);
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
                print "------------ NEW VOTE! -----------\n";
                print $this->last_vote['authorperm'] . "\n";
                print $this->last_vote['time'] . "\n";
                print "----------------------------------\n";
                if (!$this->hasVoted($voter_account)) {
                    if ($this->auto_vote) {
                        print "Voting...\n";
                        // vote here...
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

}