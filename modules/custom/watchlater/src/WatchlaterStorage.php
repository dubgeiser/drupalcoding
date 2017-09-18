<?php

namespace Drupal\watchlater;

use Drupal\Core\Database\Connection;


/**
 * The storage for the watchlater module.
 *
 * @author <per.juchtmans@digipolis.gent>
 */
class WatchlaterStorage implements WatchlaterStorageInterface
{
  protected $connection;

  public function __construct(Connection $connection)
  {
    $this->connection = $connection;
  }

  public function add($nid, $uid)
  {
    $this->connection->merge('watchlater')
      ->fields(['nid' => $nid, 'uid' => $uid])
      ->condition('nid', $nid)
      ->condition('uid', $uid)
      ->execute()
      ;
  }

  public function delete($nid, $uid)
  {
    $this->connection->delete('watchlater')
      ->condition('nid', $nid)
      ->condition('uid', $uid)
      ->execute()
      ;
  }

  public function isInList($nid, $uid)
  {
    $result = $this->connection->select('watchlater')
      ->fields(['nid', 'uid'])
      ->condition('nid', $nid)
      ->condition('uid', $uid)
      ->execute()
      ->fetch()
      ;

    return !empty($result);
  }

  public function getUserList($uid)
  {
    return $this->connection->select('watchlater')
      ->fields(['nid', 'uid'])
      ->condition('uid', $uid)
      ->execute()
      ->fetchCol()
      ;
  }
}
