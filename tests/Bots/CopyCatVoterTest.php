<?php
require '../../vendor/autoload.php';

$SteemServiceLayer = new \SteemTools\SteemServiceLayer();
$api = new \SteemTools\SteemAPI($SteemServiceLayer);

$config = array(
    //'follow_vote_comments' => true,
    //'looptime_in_seconds' => 1,
    //'reminder_in_seconds' => 3,
    //'auto_vote' => true
);

$CopyCatVoter = new \SteemTools\Bots\CopyCatVoter($api, $config);

$account = 'lukestokes';
$benefactor_account = 'robinhoodwhale';

//$result = $CopyCatVoter->getLastVote($account);

$CopyCatVoter->run($account,$benefactor_account);

// vote lukestokes "lukestokes" "are-you-building-a-business-or-a-hobby" 100 true

/*
        op = transactions.Vote(
            **{"voter": voter,
               "author": post_author,
               "permlink": post_permlink,
               "weight": int(weight * STEEMIT_1_PERCENT)}
        )
        wif = self.wallet.getPostingKeyForAccount(voter)
        return self.executeOp(op, wif)
*/

/*
def executeOp(self, op, wif=None):
        """ Execute an operation by signing it with the ``wif`` key and
            broadcasting it to the Steem network
            :param Object op: The operation to be signed and broadcasts as
                              provided by the ``transactions`` class.
            :param string wif: The wif key to use for signing a transaction
            **TODO**: The full node could, given the operations, give us a
            set of public keys that are required for signing, then the
            public keys could used to identify the wif-keys from the wallet.
        """
        # overwrite wif with default wif if available
        if not wif:
            raise MissingKeyError

        ops = [transactions.Operation(op)]
        expiration = transactions.formatTimeFromNow(30)
        ref_block_num, ref_block_prefix = transactions.getBlockParams(self.rpc)
        tx = transactions.Signed_Transaction(
            ref_block_num=ref_block_num,
            ref_block_prefix=ref_block_prefix,
            expiration=expiration,
            operations=ops
        )
        tx = tx.sign([wif])
        tx = transactions.JsonObj(tx)

        if self.debug:
            log.debug(str(tx))

        if not self.nobroadcast:
            try:
                self.rpc.broadcast_transaction(tx, api="network_broadcast")
            except:
                raise BroadcastingError
        else:
            log.warning("Not broadcasting anything!")

        return tx
*/