<?php

/*****
* File :    namana.php
* Created:  11/06/2003 10:43
* Update:   22/03/2005 23:41 version 2.0
* Function: sary upload, delete etc...
* Author:   Eugene Heriniaina
* Email:    hery@serasera.org
* Comment:
*
*******/

if ( !defined('INDEX_PASS') )
{
	die("Hacking attempt? HORERA");
}

if($authorized) {
	switch($asa) {
		case "tsikera":
			$x_title = "Hevitra momba ny sary";
			$x_body = "";
			if($saryid) {
				$sary = $DB->get_row("SELECT u.user, s.* FROM #__namana_sary s LEFT JOIN #__namana_user_profile u ON s.uid=u.uid WHERE saryid='$saryid'");

				if($sary->manokana == 1 && !is_namany($sary->uid) && $USER->uid != $sary->uid) {
					$x_body .= "Tsy nahazo alalana hijery an'io sary io ianao.";
				} elseif($sary->blockcomment == 1) {
					$x_body .= "Tsy neken'ny tompony hasiana hevitra io sary io";
				} else {

					$nbcomments = 0; //initialize nbcomments

					if($del && ($admin[sary][level] >= LEVEL_DEL || $sary->uid == $USER->uid)) {
						$qry = $DB->query("DELETE FROM #__namana_sary_comments WHERE id='$del'");
						$nbcomments = $DB->get_var("SELECT count(id) FROM #__namana_sary_comments WHERE saryid='$saryid'");
						$qry = $DB->query("UPDATE #__namana_sary SET comments = $nbcomments WHERE saryid='$saryid'");
						del_cache("namana|sary|comments|" . $saryid);
					}
					if($alefa && !is_blocked($sary->user)) {
						// nampiditra comment

						if($comment) {
							$daty = gmmktime();
							$qry = $DB->query("INSERT INTO #__namana_sary_comments (user, saryid, comment, daty) VALUES('$USER->username', '$saryid', '$comment', $daty)");
							$nbcomments = $DB->get_var("SELECT count(id) FROM #__namana_sary_comments WHERE saryid='$saryid'");
							$qry = $DB->query("UPDATE #__namana_sary SET comments = $nbcomments WHERE saryid='$saryid'");
							del_cache("namana|sary|comments|" . $saryid);
						}
					}
					$debut = ($debut)? $debut : 0;
					$limite = 20;
					$total = ( $nbcomments > 0 ) ? $nbcomments : $DB->get_var("SELECT count(id) FROM  #__namana_sary_comments WHERE saryid='$saryid' ");

					$comments = get_cache("SELECT * FROM #__namana_sary_comments WHERE saryid='$saryid' ORDER BY id LIMIT $debut, $limite", -1, "namana|sary|comments|" . $saryid);
					$sary->comments = $comments;
					$smarty->assign("sary", $sary);

					if(is_blocked($sary->user)) {
						$smarty->assign("blocked", true);
					}


					$smarty->assign("debut", $debut);
					$smarty->assign("limite", $limite);
					$smarty->assign("total", $total);

					$x_body .= $smarty->fetch($CFG->moddir . "namana/templates/sary_comments.htm");
				}
			}
		break;
		case "blog":
			$x_title = "Sary halefa any amin'ny blaogy.com";
			$x_body = "";
			if($saryid) {
				if(!$hash) {
					// satria efa md5-ed ny pass dia tsy maintsy takiana indray
					$x_body = "Ampidiro eto ambany ny tenimiafina fampiasanao eto amin'ny serasera </b>
					<form method='post' action=''>
						<input type='hidden' name='rub' value='namana/sary'>
						<input type='hidden' name='asa' value='blog'>
						<input type='hidden' name='saryid' value='" . $saryid . "'>
						<input type='password' name='hash' value=''>
						<input type='submit' name='alefa' value='Alefa' class=''>
					</form>
					";
				} else {





					include_once "ixrclass.php";
					$client = new IXR_Client('blaogy.com', '/xmlrpc.php', 80);
					if(!$client->query('blogger.getUsersBlogs', null, $USER->username, $hash)) {
						$x_body = "<b>Misy olana.</b> <br />
						- Jereo fa sao dia diso ny tenimiafina napetrakao: $hash<br />
						- Raha tsy mbola manana blaogy ianao dia manokafa blaogy ao amin'ny <a href='http://blaogy.com'>blaogy.com</a><br />
						- Raha efa manana blaogy ao amin'ny blaogy.com ianao kanefa mbola mitranga ity dia misy olana kely izany ny fifandraisana amin'ny blaogy.com fa miverena rehefa kelikely.<br>
						- Raha tena mbola tsy mety dia manorata any amin'i hery azafady
						";
					} else {
						//vérifier-na aloha fa sao dia tsy sariny akory
						$sary = $DB->get_row("SELECT * FROM #__namana_sary WHERE saryid='$saryid'");
						if(trim($alefa) == md5('alefa')) {
							if($sary->uid == $USER->uid) {
								
								$blaogy = $client->getResponse();
								
								//jerena raha ao amin'ny media ny sary 
								$saryfile = S_PATH_BASE . "media/sary/namana/". $sary->saryfile;

								// 03/05/2008 7.52
								//ajanona aloha fa mbola tsy mety ny blaogy.com 
								/*
								if(file_exists($saryfile)) {
									//uplod to blaogy 
									include('mime_type.php');
									$handle = @fopen($saryfile, "r");
									if ($handle) {
									   while (!feof($handle)) {
										   $lines[] = fgets($handle, 4096);
									   }
									   fclose($handle);
									}
									$bits = base64_encode(join("", $lines));
									$struct = array(
										"name" => $sary->saryfile,
										"type" => mime_content_type($saryfile),
										"bits" => $bits
									);
									if($blaogy_img = $client->query('metaWeblog.newMediaObject', $blaogy[0]["blogid"], $USER->username, $hash, $struct)) {
										var_dump($blaogy_img);
										$blaogy_sary_url = $blaogy_img['url'];
									} else {
										$blaogy_sary_url = $sary->sarypath . $sary->saryfile;
									}

								} else {
								*/
									//copier-na ohatra ny taloha
									$blaogy_sary_url = $sary->sarypath . $sary->saryfile;
								/*
								}
								*/
								//upload to blaogy aloha

								$postText = htmlentities(strip_tags($postText), ENT_QUOTES);

								if($sary->sarywidth > 250) {
									$sarywidth = 250;
								} else {
									$sarywidth = $sary->sarywidth;
								}
								$tovona = "";
								$tovana = "";
								switch($postPicpos) {
									case 1:
										$float = "clear: both;";
										$tovana = $postText;
									break;
									case 2:
										$float = "float: right;";
										$tovana = $postText;
									break;
									case 3:
										$float = "clear: both;";
										$tovona = $postText;
									break;
									case 4:
										$float = "float: left;";
										$tovana = $postText;
									break;
									default:
										$float = "float: left;";
										$tovana = $postText;
									break;
								}

								$description = $tovona . "<div style=\"" . $float . " margin: 5px;  width: " . ($sarywidth + 6) . "px;border: 1px solid #CCCCCC; padding: 3px;\"><a href=\"" . $blaogy_sary_url ."\" title=\"" . $USER->username . "\" style=\"text-decoration: none;\"><img src=\"" . $blaogy_sary_url ."\" width=\"" . $sarywidth . "\" alt=\"" . $USER->username . "\" border=\"0\" /></a></div>" . $tovana;

								$struc = array(
									"title" => htmlentities($postTitle, ENT_QUOTES),
									"description" => nl2br($description) . "<br clear=\"all\" />"
								);

								if($client->query('metaWeblog.newPost', $blaogy[0]["blogid"], $USER->username, $hash, $struc, true)) {
									$x_body .= "Tafiditra soa amantsara ao amin'ny blaogy ilay sary teo. Jereo ao amin'ny blaoginao <a href=\"http://" . $USER->username . ".blaogy.com\">http://" . $USER->username . ".blaogy.com</a>";
								} else {
									$x_body .= "Nisy olana ka tsy tafiditra ny sary. Ilazao i Hery azafady dia lazao aminy ity hadisoana eto ambany ity : <br /><b>" . $client->getErrorMessage() . "</b>";
								}

							} else {
								$x_body .= "Tsy azonao ampidirina amin'ny blaoginao ny sarin'olona hafa";
							}
						} else {
							//saisie
							$x_body .= "
							Raha tianao hapetraka miaraka amin'ny lahatsoratra any amin'ny blaoginao io sary io dia apetraho eto ambany ny lohateny sy ny lahatsoratra<br />
							Ny sarinao ihany no azonao ampidirina amin'ny blaoginao.<br />
							<form method='post' action='' onSubmit=\"this.bouton.value='Mahandrasa kely...';this.bouton.disabled=true;\">
							<input type='hidden' name='saryid' value='$saryid'>
							<input type='hidden' name='rub' value='$rub'>
							<input type='hidden' name='asa' value='$asa'>
							<input type='hidden' name='hash' value='$hash'>
							<input type='hidden' name='alefa' value='" . md5('alefa') ."'>
							<table>
								<tr>
								<td valign='top'>
									<b>Lohateny : </b>
								</td>
								<td valign='top'>
									<input type='text' name='postTitle' value='' class='gen_input'>
								</td>
								</tr>
								<tr>
								<td valign='top'>
									<b>Lahatsoratra : </b>
								</td>
								<td valign='top'>
									<textarea name='postText' rows='10' cols='35' class='gen_input' ></textarea>
								</td>
								</tr>
								<tr>
								<td valign='top'>
									<b>Toeran'ny sary : </b>
								</td>
								<td valign='top'>
									<input type='radio' name='postPicpos'value='4' checked> Ankavian'ny lahatsoratra<br />
									<input type='radio' name='postPicpos'value='2' > Ankavanan'ny lahatsoratra<br />
									<input type='radio' name='postPicpos'value='1' > Ambonin'ny lahatsoratra<br />
									<input type='radio' name='postPicpos'value='3' > Ambanin'ny lahatsoratra<br />
								</td>
								</tr>
								<tr>
								<td valign='top' align='center' colspan='2'>
									<input type='submit' name='bouton' value='   Alefa   '>
								</td>
								</tr>
							</table>
							</form>
							";

						}
					}





				}

			} else {
				$x_body .= "Tsy nifidy sary halefa any amin'ny blaogy ianao";
			}

		break;
		case "del":
			$x_title = "Hamafa sary";
			$x_body = "";
			$uid = $DB->get_var("SELECT uid FROM #__namana_sary WHERE saryid = '$saryid' LIMIT 1");
			if($admin[sary][level] >= LEVEL_DEL || $uid == $USER->uid) {
				if(!$confirm) {
					$x_body .= "
					<form action='index.php' method='post'>
					<input type='hidden' name='rub' value='$rub'>
					<input type='hidden' name='asa' value='del'>
					<input type='hidden' name='saryid' value='$saryid'>
						<table cellspacing='0' cellpadding='0' align=center border='0'>
						<tr>
							<td colspan='3' align='center'>
								Tianao hofafaina tokoa ve io sary io ?
							</td>
						</tr>
					";
					if ($admin[sary][level] >= LEVEL_DEL && $uid != $USER->uid) {
						$x_body .= "
						<tr>
							<td colspan='3' align='center'>
							Ianao dia mpitantana ny sehatra. Ampidiro eto ambany <b>amim-panajana </b>ny antony hanesoranao an'io sarin'ny namana io hampitaina aminy. <br>
								<textarea name='antony' rows='5' cols='25'>Manahoana \n Voatery nesorina ny sary napetrakao satria tsy mifanaraka amin'ny zavatra ilainay.</textarea>
							</td>
						</tr>
							";
					}
					$x_body .= "
					<tr>
						<td align='right' valign='top'>
							<input type='submit' name='confirm' value=\"    Tsia   \">
						</td>
						<td>&nbsp;&nbsp;&nbsp;</td>
						<td align='left' valign='top'>
							<input type='submit'  name='confirm' value=\"    Eny    \">
						</td>
					</tr>
					</table><br><br>
					</form>
					";

			} elseif(trim($confirm) == "Eny") {

				if($sary = $DB->get_row("SELECT * FROM #__namana_sary WHERE saryid='$saryid' LIMIT 1")) {
					$ftp_server = $sary->ftp_server;
					$ftp_user_name = $sary->ftp_user_name;
					$ftp_user_pass = $sary->ftp_user_pass;
					$ftp_dir = $sary->ftp_dir;

					$rst = $DB->query("DELETE FROM #__namana_sary WHERE saryid='$saryid'");
					$rst = $DB->query("DELETE FROM #__namana_sary_comments WHERE saryid='$saryid'");
					$query = $DB->query("UPDATE #__namana_user_profile SET sary =" . $DB->get_var("SELECT count(*) FROM #__namana_sary WHERE uid='$sary->uid'") . " WHERE uid='$sary->uid' LIMIT 1");
					delete_pic($sary->saryfile);

					if($sary->uid == $USER->uid) { // ny tompony no mamafa
						$message = "Miarahaba an'i $USER->user,\n\nAraka ny fangatahanao dia nesorina ny sarinao iray.\n\nRaha mbola te hampiditra sary dia ao amin'ny\n\nhttp://namana.serasera.org/?rub=namana/sary\nMisaotra betsaka.";

						$subject = "Sary nesorina";
						send_mail($USER->user . "<" . $USER->email . ">", $subject, $message);
						$message = "Nesorin'i $USER->user ny sariny";

						$subject = "[Admin sary] Sary nesorina";
						send_mail("<admin@serasera.org>", $subject, $message);
						$x_body .= "Voafafa ny sary.";
					} else { // admin no mamafa
						if($tompony = $DB->get_row("SELECT * FROM #__namana_user_profile WHERE uid='" . $sary->uid . "' LIMIT 1")) {
							$x_body .= "Voafafa ny sary. Nandefasana  hafatra ny tompon'ny sary.";
							$message = "Miarahaba an'i $tompony->user,\n\nIalana tsiny fa voatery nofafain'i $USER->user ny sarinao.\nIty ambany ity ny antony nanesorany azy :\n\n[b]" . $antony . "[/b]\n\nRaha mbola te hampiditra sary dia ao amin'ny\n\nhttp://namana.serasera.org/?rub=namana/sary\nMisaotra betsaka.";
							$subject = "Sary nesorina";

							send_message($tompony->user, $subject, $message, $USER->username);

							$tatitra .= "<b>$subject</b>\n\n";
							$tatitra .= "- Nesorin'i <b>$USER->user</b> ny sarin'i <b>$tompony->user</b>. Ny antony dia\n\n". $antony ;
							$message = "Nesorin'i $USER->user ny sarin'i $tompony->user satria $antony";

							$subject = "Sary nesorina";
							send_mail("<admin@serasera.org>", $subject, $message);
						}
					}
				} else {
					$x_body .= "Tsy ao intsony ny sary tianao hofafaina";
				}
			} else {
				$x_body .= "Tsy voafafa ny sary.";
			}
		} else {
			$x_body .= "Tsy afaka mamafa sary fa niveau " .$admin[sary][level];
		}
		break;
		case "add":

			$x_title = "Hampiditra sary";
			$x_body = "";
			if (trim($alefa) == md5('alefa')){
				$photo_sql_upd = "";
				$photo_sql_ins_fields = "";
				$photo_sql_ins_values = "";

				if (isset($_FILES['sary'])){

					$photodir = S_PATH_BASE . "media/sary/namana/";

					include_once (S_PATH_BASE . "library/classes/rc/rc_uploader.class.php");
					$up = new rc_uploader;
					$up->setMaxFilesize(512000); // set internal value for max. filesize for uploads to 512kb
					$fts = array("jpg", "png", "jpeg", "gif"); // create a list with allowed filetypes
					$up->setAllowedFiletypes($fts); // allow these extensions to be uploaded. all others will be declined.
					if($up->upload("sary", $photodir, 2)) {
						$PicsInArray = array();
						$PicsInArray = $up->getReportSuccess();
						//print_r($PicsInArray);
						if($saryfile = $PicsInArray[0][name_renamed]) {

							$size = GetImageSize($photodir . $saryfile);
							$imagewidth = $size[0];
							$imageheight = $size[1];
							$imagetype = $size[2];
							if($imagewidth > 600) {
								make_thumbnail($photodir . $saryfile, 600, $photodir . $saryfile);
							}
							make_thumbnail($photodir . $saryfile, 150, $photodir . "s" . $saryfile);

							
							// upload thumbnail to ftp
							$sresult = put_mirror("sary_namana", "s".$saryfile,  $photodir."s".$saryfile);
							$result = put_mirror("sary_namana", $saryfile,  $photodir.$saryfile);
							if($saryid) {
								$photo_sql_upd = ", sarypath='$result->ftp_path', saryfile='$saryfile', sarywidth='$imagewidth', saryheight='$imageheight', ftp_server='$result->ftp_server', ftp_user_name='$result->ftp_user_name', ftp_user_pass='$result->ftp_user_pass', ftp_dir='$result->ftp_dir' ";
							} else {
								$photo_sql_ins_fields = " ,sarypath, saryfile, sarywidth, saryheight, ftp_server, ftp_user_name, ftp_user_pass, ftp_dir ";
								$photo_sql_ins_values = " , '$result->ftp_path', '$saryfile', '$imagewidth', '$imageheight', '$result->ftp_server', '$result->ftp_user_name', '$result->ftp_user_pass', '$result->ftp_dir' ";
							}
						} else {
							$x_body .= "<div>Tsy tafiditra tao amin'ny toerany ny sary. Ilazao ny mpitantana. Error: " . __LINE__ . "</div>";
						}
					}
				}
				if($saryid) {
					$query = $DB->query("UPDATE #__namana_sary SET sarylabel='$sarylabel', manokana='$manokana', blockcomment='$blockcomment' $photo_sql_upd WHERE saryid='$saryid' LIMIT 1");
				} else {
					if($saryfile) {
						$saryid = uniqid("s");
						$query = $DB->query("INSERT INTO #__namana_sary (saryid, uid, sarylabel, manokana, blockcomment $photo_sql_ins_fields) VALUES ('$saryid', '$USER->uid', '$sarylabel', '$manokana', '$blockcomment' $photo_sql_ins_values)");
					} else {
						$x_body .= "Tsy tafiditra ny sary. Raha tsy adinonao ny nampiditra sary dia ilazao ny mpandrindra. Error: " . __LINE__ . ".";
					}
				}

					//@unlink($photodir.$saryfile);
				if(($DB->rows_affected > 0 || $DB->insert_id)) {
					if($saryfile) {
						$query2 = $DB->query("UPDATE #__namana_user_profile SET sary =" . $DB->get_var("SELECT count(*) FROM #__namana_sary WHERE uid='$USER->uid'") . " WHERE uid='$USER->uid' LIMIT 1");
						$x_body .= "Tafiditra soa amantsara ny sary nampidirinao";
						$tmp_subject = "Nampiditra sary $USER->username";
						if($manokana == 1) {
							$txt_manokana = " [b]manokana[/b] ";
						}
						$tmp_message = "Manahoana,\n\n Nampiditra sary $txt_manokana i $USER->username\n\n Ity ambany ity ny sary nampidiriny.\n\n [img]".$result->ftp_path."s".$saryfile."[/img]\n\n na \n".$result->ftp_path.$saryfile."\n";
						
						if(!$manokana) {
							$tmp_message .= "Raha tianao ho fafaina io sary io dia tsindrio ny rohy eto ambany \n [url=http://namana.serasera.org/?rub=namana/sary&asa=del&saryid=$saryid]Fafao ny sary[/url]";
						}
						send_mail("admin@serasera.org", $tmp_subject, $tmp_message, "Admin", $type = "html");
						//@omeo_isa($USER->uid, 5);
					} else {
						$x_body .= "Vita ny fanitsiana ny sary.";
					}

				} else {
					$x_body .= "Tsy nisy fanovana natao.";
				}

			} else {

				if($saryid) {
					$sary = $DB->get_row("SELECT * FROM #__namana_sary WHERE saryid='$saryid' LIMIT 1");
					if($sary->uid != $USER->uid) {
						$sary = null;
					}
				}

				$x_body .= "
	Eto no toerana hampidiranao ny sarinao
	<form method='post' action='index.php' enctype='multipart/form-data' onSubmit=\"this.bouton.value='Mahandrasa kely...';this.bouton.disabled=true;\">
	<input type='hidden' name='MAX_FILE_SIZE' value='512000'>
	<input type='hidden' name='saryid' value='$sary->saryid'>
	<input type='hidden' name='rub' value='$rub'>
	<input type='hidden' name='asa' value='add'>
	<input type='hidden' name='alefa' value='" . md5('alefa') ."'>
	<table cellpadding='0' cellspacing='5' align=center border=0>
	<tr>
		<td>
		<b>:: Fanamarihana manokana momba ny sary</b><br>
		- Ny sary ahitana ny endrikao ihany no ilainay. Tsy voatery mametraka sary raha tsy manana.<br />
		- Tsy mihoatra ny <b>300 Ko</b> ny habeny<br />
		- <b>jpeg</b> na <b>gif</b> ihany no format raisina<br />
		- Ny namana mametraka sary maloto sy mamoafady dia <font color='#FF0000'><b>esorina tsy ho namana</b></font> avy hatrany<br />
		- <a href='http://namana.serasera.org/?rub=pages/page&page_id=p46e8ed7f0e11c'>Jereo eto</a> fitsipika hafa momba ny sary.
		</td>
	</tr>
	<tr>
		<td  align='center'><b>Mombamomba ny sary : </b><br>
		<textarea name='sarylabel' cols='25' rows='5' class='gen_input'>$sary->sarylabel</textarea>
		</td>
	</tr>
	<tr>
		<td align='center'><b>Ho an'ny namako manokana. <a href='index.php?rub=namana/namako'>Jereo eto ny lisitra</a><input type='checkbox' name='manokana' value='1' ";
		$x_body .= ($sary->manokana == 1 ) ? " checked " : "";
		$x_body .=	">
		</td>
	</tr>
	<tr>
		<td align='center'><b>Tsy azo asiana hevitra. <input type='checkbox' name='blockcomment' value='1' ";
		$x_body .= ($sary->blockcomment == 1 ) ? " checked " : "";
		$x_body .=	">
		</td>
	</tr>
	<tr>
			  <td valign='top' align='center'>
				   <input type='file' name='sary' class='gen_input'>
			  </td>
		 </tr>
		 <tr>
			  <td valign='top' align='center'>
				   <input type='submit' name='bouton' class='button' value='        Alefa        '>
			  </td>
		 </tr>
	</table>
	</form>
			";

				$smarty->assign('body_title', $x_title);
				$smarty->assign('body_content', $x_body);
				$body_main[] = $smarty->fetch("body.htm");

				$x_title = "Lisitry ny sarinao";
				$x_body = "";

				$x_body .= "Ireto ny lisitry ny sarinao " ;

				$debut = ($debut) ? $debut : 0;
				$limite = 20;
				$total = $DB->get_var("SELECT count(*) FROM #__namana_sary WHERE uid='" . $USER->uid . "'");

				$sary_miseho = ($total > $limite) ? ($debut +1) ." - " . ($debut +$limite) . "/" . $total :  ($debut +1) ." - " . ($debut +$total) . "/" . $total ;

				$sql = "SELECT * FROM #__namana_sary WHERE uid='" . $USER->uid . "' ORDER BY id DESC LIMIT $debut, $limite";
				$sary = $DB->get_results($sql);
				$x_body .= "

				<table align='center' width='100%' border=0 cellspacing=1 cellpadding=1 bgcolor='$tbgcolor[0]'>
				  <tr bgcolor='$tbgcolor[3]'>
					<td valign='top' align='' class=''>
				<table border=0 cellspacing=1 cellpadding=1 >
				  <tr >
					<td align='' class=''><b>Sary  miseho : </b> " . $sary_miseho . "
					</td>
				  </tr>
				</table>
					</td>
				  </tr>
				</table><br>
				";

				$smarty->assign("debut", $debut);
				$smarty->assign("limite", $limite);
				$smarty->assign("total", $total);
				$smarty->assign("sary", $sary);
				$x_body .= $smarty->fetch($CFG->moddir . "namana/templates/sary.htm");
				$x_body .= $smarty->fetch("pager.htm");
			}
		break;
		default:
			$x_title = "Lisitry ny sary";
			$x_body = "";

			if($USER->namana == 0) {
				$x_body .= profile_empty();
			} else {
				if($uid || $user) {
					$the_user = $DB->get_row("SELECT * FROM #__namana_user_profile WHERE uid='$uid' OR user='$user' ");
					$x_body .= "Ireto ny lisitry ny sarin'i " . $the_user->user ;

					if(!is_namany($the_user->uid) && $USER->uid != $the_user->uid) {
						$where_clause = " AND s.manokana=0 ";
					} else {
						$where_clause = "";
					}
					$debut = ($debut) ? $debut : 0;
					$limite = 20;
					$total = $DB->get_var("SELECT count(*) FROM #__namana_sary s WHERE uid='" . $the_user->uid . "' $where_clause");

					$sary_miseho = ($total > $limite) ? ($debut +1) ." - " . ($debut +$limite) . "/" . $total :  ($debut +1) ." - " . ($debut +$total) . "/" . $total ;

					$sql = "SELECT u.user, s.* FROM #__namana_sary s LEFT JOIN #__namana_user_profile u ON s.uid=u.uid WHERE s.uid='" . $the_user->uid . "' $where_clause ORDER BY s.id DESC LIMIT $debut, $limite";
					$sary = $DB->get_results($sql);
					$x_body .= "
					<br><br>

			<table align='center' width='100%' border=0 cellspacing=1 cellpadding=1 bgcolor='$tbgcolor[0]'>
			  <tr bgcolor='$tbgcolor[3]'>
				<td valign='top' align='' class=''>
			<table border=0 cellspacing=1 cellpadding=1 >
			  <tr >
				<td align='' class=''><b>Sary  miseho : </b> " . $sary_miseho . "
				</td>
			  </tr>
			</table>
				</td>
			  </tr>
			</table><br>
					";

					$smarty->assign("debut", $debut);
					$smarty->assign("limite", $limite);
					$smarty->assign("total", $total);
					$smarty->assign("sary", $sary);
					$x_body .= $smarty->fetch($CFG->moddir . "namana/templates/sary.htm");
					$x_body .= $smarty->fetch("pager.htm");
				} else {
					//list

					$debut = ($debut) ? $debut : 0;
					$limite = 20;
					$total = $DB->get_var("SELECT count(*) FROM #__namana_sary WHERE manokana=0 ");

					$sary_miseho = ($total > $limite) ? ($debut +1) ." - " . ($debut +$limite) . "/" . $total :  ($debut +1) ." - " . ($debut +$total) . "/" . $total ;

					switch($order) {
						case "tsikera":
							$orders = "s.comments DESC, s.id DESC";
							break;
						default:
							$orders = "s.id DESC";
							break;
					}

					$sary = $DB->get_results("SELECT s.*, u.user "
					. "\nFROM #__namana_sary s "
					. "\nLEFT JOIN #__namana_user_profile u ON s.uid=u.uid "
					. "\nWHERE s.manokana=0 "
					. "\nORDER BY $orders LIMIT $debut, $limite");

					$x_body .= "
					Lisitry ny sarin'ny namana<br><br>
<a href=\"?rub=namana/sary&order=tsikera\">Alaharo araka ny hevitry ny namana</a><br />
<a href=\"?rub=namana/sary&order=daty\">Alaharo araka ny daty</a><br />
			<table align='center' width='100%' border=0 cellspacing=1 cellpadding=1 bgcolor='$tbgcolor[0]'>
			  <tr bgcolor='$tbgcolor[3]'>
				<td valign='top' align='' class=''>
			<table border=0 cellspacing=1 cellpadding=1 >
			  <tr >
				<td align='' class=''><b>Sary  miseho : </b> " . $sary_miseho . "
				</td>
			  </tr>
			</table>
				</td>
			  </tr>
			</table><br>
					";
					$smarty->assign("debut", $debut);
					$smarty->assign("limite", $limite);
					$smarty->assign("total", $total);
					$smarty->assign("sary", $sary);
					$x_body .= $smarty->fetch($CFG->moddir . "namana/templates/sary.htm");
					$x_body .= $smarty->fetch("pager.htm");

				}

			}

		break;
	}
	$smarty->assign('body_title', $x_title);
	$smarty->assign('body_content', $x_body);
	$body_main[] = $smarty->fetch("body.htm");

} else {
	not_registered();
@include "box_pub.php";
}

?>
