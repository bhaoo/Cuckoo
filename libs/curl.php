<?php
/**
 * CURL
 *
 * @author ohmyga
 * @version 2.0.0
 * @date 2020-03-24
 */

class curl {

  /**
   * POST
   *
   * @param $url
   * @param $data
   * @param $cookie
   * @param $head
   * @return $output
   */
  public static function post($url, $data, $head = NULL, $cookie = NULL) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);

    if ($head != NULL) {
      curl_setopt($curl, CURLOPT_HTTPHEADER, $head);
    }

    if ($cookie != NULL) {
      curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    }

    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    $execs = curl_exec($curl);

    print_r(curl_error($curl));
    curl_close($curl);

    $output = $execs;

    return $output;
  }

  /**
   * Get
   *
   * @param $url
   * @param $data
   * @param $cookie
   * @param $head
   * @return $output
   */
  public static function get($url, $data = NULL, $head = NULL, $cookie = NULL) {
    if ($data != NULL) {
      $url = $url.'?'.http_build_query($data);
    }
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    if ($head != NULL) {
      curl_setopt($curl, CURLOPT_HTTPHEADER, $head);
    }
    if ($cookie != NULL) {
      curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    }
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);

    return $output;
  }
}