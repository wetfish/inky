<?php
	function isLoggedIn($returnBool=false){
		global $DB,$session_id,$session_id_esc;
		$q = $DB->query("SELECT * FROM `accounts` WHERE `SessionId` = '$session_id_esc' LIMIT 1");
		if($returnBool){
			if($DB->count($q))
				return true;
			else
				return false;
		}
		else{
			return $q;
		}
	}
	function loginForm(){
		global $DB;
		$q = isLoggedIn();
		echo "<p>You are logged in as  ";
                if($DB->count($q)){
                        $r = $DB->fetch($q);
			echo $r['Username'];
			echo "<br/><a href='/?p=logout'>Want to log out?</a>";
                }
		else{
			echo "Guest.</p><p>";
			echo "<form action='/?p=login' method='post'>";
			echo "<table>";
			echo "<tr><td>Username</td><td><input name='user'/></td></tr>";
			echo "<tr><td>Password</td><td><input type='password' name='pass'/></td></tr>";
			echo "<tr><td>&nbsp;</td><td><input type='submit' value='Login &rarr;'/></td></tr>";
			echo "</table>";
			echo "</form>";
		}
		echo "</p>";
	
	}
	function registerForm(){
                if(!isLoggedIn(true)){
			echo "<p>Want an account?</p>";
                        echo "<p>";
                        echo "<form action='/?p=register' method='post'>";
                        echo "<table>";
                        echo "<tr><td>Username</td><td><input name='user'/></td></tr>";
                        echo "<tr><td>Password</td><td><input type='password' name='pass'/></td></tr>";
                        echo "<tr><td>&nbsp;</td><td><input type='submit' value='Register &rarr;'/></td></tr>";
                        echo "</table>";
                        echo "</form>";
			echo "</p>";
                }

        }

?>
