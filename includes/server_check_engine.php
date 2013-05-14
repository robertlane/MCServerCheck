<?php
/*
 * Copyright (c) 2012 Matt Harzewski
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and
 * associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class MCServerStatus {

	public $server;
	public $online, $motd, $online_players, $max_players;
	public $error = "OK";

	function __construct($url, $port = '25565') {

		$this->server = array(
			"url" => $url,
			"port" => $port
		);

		if ( $sock = @stream_socket_client('tcp://'.$url.':'.$port, $errno, $errstr, 1) ) {

			$this->online = true;

			fwrite($sock, "\xfe");
			$h = fread($sock, 2048);
			$h = str_replace("\x00", '', $h);
			$h = substr($h, 2);
			$data = explode("\xa7", $h);
			unset($h);
			fclose($sock);

			if (sizeof($data) == 3) {
				$this->motd = $data[0];
				$this->online_players = (int) $data[1];
				$this->max_players = (int) $data[2];
			}
			else {
				$this->error = "Cannot retrieve server info.";
			}

		}
		else {
			$this->online = false;
			$this->error = "Cannot connect to server.";
		}

	}

}

?>
