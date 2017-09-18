<?php

namespace Drupal\watchlater;


/**
 * The storage interface for the watchlater module.
 *
 * @author <per.juchtmans@digipolis.gent>
 */
interface WatchlaterStorageInterface
{
  public function add($nid, $uid);
  public function delete($nid, $uid);
  public function isInList($nid, $uid);
  public function getUserList($uid);
}


