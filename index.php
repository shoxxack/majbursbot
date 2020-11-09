<?php
define("UZKOD","1429530214:AAHz3fufU_j6UD3wDNsa4DCn9cAM4cj9Ums");
$admin = "1232898350";

function  top($chatid){
$text = "👥 <b>TOP 20'ta eng ko'p odam qo'shgan foydalanuvchilar ro'yxati ☝️:</b>\n\n";
$files = glob("bot/$chatid/*.db");
foreach ($files as $user) {
$id = str_replace(["bot/$chatid/", ".db"], ["",""],$user);
$data[$id] = file_get_contents($user);
}
arsort($data);
$i = 1;
foreach ($data as $id=>$son) {
if ($i > 20)break;
$us = bot ('getChatMember', [
'chat_id'=>$chatid,
'user_id'=>$id,
]);
$res = $us->result->user->first_name;
$text .= "<b>$i)</b> <a href='tg://user?id=$id'>$res</a> <b>- [$son]</b>\n";
$i++;
}
return $text;
}

function bot($method,$datas=[]){
$url = "https://api.telegram.org/bot".UZKOD."/".$method;
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
$res = curl_exec($ch);
if(curl_error($ch)){
var_dump(curl_error($ch));
}else{
return json_decode($res);
}
}

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$cid = $message->chat->id;
$cty = $message->chat->type;
$mid = $message->message_id;
$name = str_replace(["[","]","(",")","*","_","`"],["","","","","","",""],$message->from->first_name);
$user = $message->from->username;
$tx = $message->text;
$uid = $message->from->id;
$reply= $message->reply_to_message->text;
$replyid = $message->reply_to_message->from->id;
$replyname = $message->reply_to_message->from->first_name;
$title = $message->chat->title;

$call = $update->callback_query;
$mes = $call->message;
$data = $call->data;
$qid = $call->id;
$callcid = $mes->chat->id;
$callmid = $mes->message_id;
$callfrid = $call->from->id;
$calluser = $mes->chat->username;
$callfname = $call->from->first_name;

$new = $message->new_chat_member;
$new_id = $new->id;
$new_name = $new->first_name;
$left = $message->left_chat_member;

$soat = date("H:i:s", strtotime("2 hour"));
$sana = date("d.m.y", strtotime("2 hour"));

$soni = file_get_contents("bot/$cid/$uid.db");
$chan = file_get_contents("bot/$cid.db");
$user = file_get_contents("bot/user.db");
$guruh = file_get_contents("bot/guruh.db");
mkdir("bot");
if ($soni == false){$soni = 0;}

$co = bot ('getChatMembersCount', [
'chat_id'=> $cid
]);
$count = $co->result;

if ($cty == "supergroup"){
if (mb_stripos($guruh,$cid)!==false){
}else{
file_put_contents("bot/guruh.db","$guruh\n$cid");
}
}

if ($cty == "private"){
if (mb_stripos($user,$uid)!==false){
}else{
file_put_contents("bot/user.db","$user\n$uid");
}
}

if ($tx =="/stat" and $cty == "private"){
$guruh = substr_count($guruh,"\n");
$user = substr_count($user,"\n");
$umum = $guruh + $user;
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"📈 <b>Bot a'zolari:</b>
👤 Userlar: <b>$user </b>
👥 Guruhlar: <b>$guruh</b>
🕵 Umumiy: <b>$umum</b>

<b>$sana $soat</b>",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"♻ Yangilash",'callback_data'=>"stat"]]
]
])
]);
}

if ($data =="stat"){
$guruh = substr_count($guruh,"\n");
$user = substr_count($user,"\n");
$umum = $guruh + $user;
bot('editmessagetext',[
'chat_id'=>$callcid,
'message_id'=> $callmid,
'text'=>"📈 <b>Bot a'zolari:</b>
👤 Userlar: <b>$user </b>
👥 Guruhlar: <b>$guruh</b>
🕵 Umumiy: <b>$umum</b>

<b>$sana $soat</b>",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"♻ Yangilash",'callback_data'=>"stat"]]
]
])
]);
}

if ($tx == "/start" and $cty == "private"){
$us = bot ('getChatMember', [
'chat_id'=>"@Hacker_Bey",
'user_id'=> $uid,
]);
$get = $us->result->status;
if ($get == "administrator" or $get == "creator" or $get == "member"){
bot ('sendmessage', [
'chat_id'=> $cid,
'parse_mode'=>"markdown",
'text'=>"🤖 *Botga xush kelibsiz,* [$name](tg://user?id=$uid)!

🌐 _Bu bot guruhga kim qancha odam qo'shganligini aytib beruvchi va kanalga a'zo bo'lmasa guruhda yoza olmaslikni ta'minlaydigan robot. ☝️Botni admin qilib tayinlashni unutmang☝️!_

/top *- Bu buyruq guruhidagi TOP 20'ta odam qo'shuvchi obunachilarni chiqarib beradi.*
/mymembers *- Guruhga nechta odam qo'shganingizni aytib beradi.*
/setchannel *- Majburiy a'zolik tizimini sozlash. Bu sizga guruh a'zolari siz istagan kanalga a'zo bo'lishmasa guruhda yoza olishmaydi.*

📛 *Eslatma:* Botni guruhga admin qilmasangiz ishlata olmaysiz!",
'reply_to_message_id'=> $mid,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"📲 Kanalimiz",'url'=>"t.me/Hacker_Bey"],['text'=>"👥 Guruhimiz",'url'=>"t.me/Hacker_Bey_group"]]
]
])
]);
}else{
bot ('sendmessage', [
'chat_id'=> $cid,
'parse_mode'=>"html",
'text'=>"🔵 <b>Kechirasiz</b> <a href='tg://user?id=$cid'>$name</a> <b>botdan to'liq foydalanish uchun</b> <a href='t.me/Hacker_Bey'>@Hacker_Bey</a> <b>kanaliga a'zo bo'ling va tekshirish tugmasini bosing!

📛 Agar kanaldan chiqib ketsangiz bot sizning guruhda ishlamay qoladi.</b>",
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"➕ A'zo bo'lish",'url'=>"t.me/Hacker_Bey"]],
[['text'=>"✅ Tekshirish",'callback_data'=>"result"]]
]
])
]);
}
}

if ($data == "result"){
$us = bot ('getChatMember', [
'chat_id'=>"@Hacker_Bey",
'user_id'=> $callfrid,
]);
$get = $us->result->status;
if ($get == "administrator" or $get == "creator" or $get == "member"){
bot ('EditMessageText', [
'chat_id'=> $callcid,
'message_id'=> $callmid,
'parse_mode'=>"markdown",
'text'=>"🤖 *Botga xush kelibsiz,* [$callfname](tg://user?id=$callcid)!

🌐 _Bu bot guruhga kim qancha odam qo'shganligini aytib beruvchi va kanalga a'zo bo'lmasa guruhda yoza olmaslikni ta'minlaydigan robot. ☝️Botni admin qilib tayinlashni unutmang☝️!_

/top *- Bu buyruq guruhidagi TOP 20'ta odam qo'shuvchi obunachilarni chiqarib beradi.*
/mymembers *- Guruhga nechta odam qo'shganingizni aytib beradi.*
/setchannel *- Majburiy a'zolik tizimini sozlash. Bu sizga guruh a'zolari siz istagan kanalga a'zo bo'lishmasa guruhda yoza olishmaydi.*

📛 *Eslatma:* Botni guruhga admin qilmasangiz ishlata olmaysiz!",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"📲 Kanalimiz",'url'=>"t.me/Hacker_Bey"],['text'=>"👥 Guruhimiz",'url'=>"t.me/Hacker_Bey_group"]]
]
])
]);
}else{
bot ('answerCallbackQuery', [
'callback_query_id'=> $qid,
'text'=>"📛 Siz @Hacker_Bey kanalimizga a'zo bo'lmadingiz!",
'show_alert'=>true,
]);
}
}

if ($new){
bot('deleteMessage', ['chat_id'=> $cid,'message_id'=> $mid]);
}

if ($new_id == 1075037022){
if ($count > 69){
bot ('sendmessage', [
'chat_id'=> $cid,
'text'=>"🌐 _Bu bot guruhga kim qancha odam qo'shganligini aytib beruvchiva kanalga a'zo bo'lmasa guruhda yoza olmaslikni ta'minlaydigan robot. Botni admin qilib tayinlashni unutmang!_

/top *- Bu buyruq guruhidagi TOP 20'ta odam qo'shuvchi obunachilarni chiqarib beradi.*
/mymembers *- Guruhga nechta odam qo'shganingizni aytib beradi.*
/setchannel *- Majburiy a'zolik tizimini sozlash. Bu tizim orqali guruh a'zolari siz istagan kanalga a'zo bo'lishmasa guruhda yoza olishmaydi.*

📛 *Eslatma:* Botni guruhga admin qilmasangiz ishlata olmaysiz!",
'parse_mode'=>"markdown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"📲 Kanalimiz",'url'=>"t.me/Hacker_Bey"],['text'=>"👥 Guruhimiz",'url'=>"t.me/Hacker_Bey_group"]]
]
])
]);
mkdir("bot/$cid");
}else{
bot ('sendmessage', [
'chat_id'=> $cid,
'text'=>"📛 <b>Kechirasiz botdan foydalanish uchun guruhda kamida 70ta a'zo bo'lishi kerak! </b>",
'parse_mode'=>"html",
]);
bot ('leaveChat', [
'chat_id'=> $cid,
]);
}
}

if ($new and $new_id != $uid){
$soni = file_get_contents("bot/$cid/$uid.db");
$son = $soni + 1;
file_put_contents("bot/$cid/$uid.db",$son);
}

if (isset($left)){
$leftid = $message->left_chat_member->id;
unlink("bot/$cid/$leftid.db");
}

if ($tx == "/mymembers" or $tx == "/mymembers@Majburs_bot"){
if ($cty == "supergroup"){
if (!$replyid){
bot ('sendmessage', [
'chat_id'=> $cid,
'text'=>"🇺🇿 <a href='tg://user?id=$uid'>$name</a> <b>siz shu kungacha guruhga</b>  <code>$soni</code><b>ta odam qo'shgansiz!</b>",
'parse_mode'=>"html",
'reply_to_message_id'=> $mid,
]);
}else{
$rsoni = file_get_contents("bot/$cid/$replyid.db");
if ($rsoni == false){$rsoni = 0;}
bot ('sendmessage', [
'chat_id'=> $cid,
'text'=>"🇺🇿 <a href='tg://user?id=$replyid'>$replyname</a> <b>shu kungacha guruhga</b>  <code>$rsoni</code><b>ta odam qo'shgan!</b>",
'parse_mode'=>"html",
'reply_to_message_id'=> $mid
]);
}
}
}

if ($tx == "/top" or $tx == "/top@Majburs_bot"){
 if ($cty == "supergroup"){
$reyting = top($cid);
bot ('sendmessage', [
'chat_id'=> $cid,
'parse_mode'=>"html",
'text'=> $reyting,
'reply_to_message_id'=> $mid,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"♻️ Yangilash", 'callback_data'=>"update"]]
]
])
]);
}
}

if($data =="update"){
$reyting = top($callcid);
bot ('editmessagetext', [
'chat_id'=> $callcid,
'message_id'=>$callmid,
'parse_mode'=>"html",
'text'=> $reyting,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"♻️ Yangilash", 'callback_data'=>"update"]]
]
])
]);
}

if ((mb_stripos($tx,"/setchannel")!==false) and (strlen($tx) > 11)){
if ($cty == "supergroup"){
$ex = explode(" ", $tx);
$us = bot ('getChatMember', [
'chat_id'=> $cid,
'user_id'=> $uid
]);
$res = $us->result->status;
if ($res == "administrator" or $res == "creator"){
$gett= bot ('getChatMember', [
'chat_id'=> $ex[1],
'user_id'=> $uid
]);
$get = $gett->result->status;
if ($get == "administrator" or $get == "creator"){
bot ('sendmessage', [
'chat_id'=> $cid,
'parse_mode'=>"html",
'text'=>"✅ <b>Kanal sozlandi. Endi guruh a'zolari</b> $ex[1] <b>kanaliga a'zo bo'lmaguncha guruhda yoza olishmaydi.</b>",
'reply_to_message_id'=> $mid
]);
file_put_contents("bot/$cid.db", $ex[1]);
}else{
bot ('sendmessage', [
'chat_id'=> $cid,
'parse_mode'=>"markdown",
'text'=>"📛 *Bot yoki siz kanalda admin emas. Xatolikni to'g'irlab qayta urunib ko'ring!*",
'reply_to_message_id'=> $mid
]);
}
}
}
}

if ($tx == "/setchannel" and (strlen($tx) == 11)){
$us = bot ('getChatMember', [
'chat_id'=> $cid,
'user_id'=> $uid,
]);
$res = $us->result->status;
if ($res == "administrator" or $res == "creator"){
bot ('sendmessage', [
'chat_id'=> $cid,
'parse_mode'=>"html",
'text'=>"🔵 <b>Ushbu buyruqdan foydalanish quyidagicha:</b>

✅<b>Namuna:</b>
<code>/setchannel @Hacker_Bey</code>",
'reply_to_message_id'=> $mid,
]);
}
}

if ($tx == "/setchannel" or $tx == "/start" or $tx == "/setchannel@MajburBot" or $tx == "/stat"){
if ($cty == "supergroup"){
$us = bot ('getChatMember', [
'chat_id'=> $chan,
'user_id'=> $uid,
]);
$get = $us->result->status;
if ($get == "member"){
bot ('deleteMessage', ['chat_id'=> $cid,'message_id'=> $mid]);
}
}
}

$chan = file_get_contents("bot/$cid.db");
if(isset($chan)){
if($cty == "supergroup"){
if (isset($tx) and $uid != 777000){
$us = bot('getchat', [
'chat_id'=>$chan
]);
$user = $us->result->username;
$tit = $us->result->title;
$us = bot ('getChatMember', [
'chat_id'=> $chan,
'user_id'=> $uid,
]);
$get = $us->result->status;
if ($get =="administrator" or $get =="creator" or $get == "member"){
}else{
bot ('deleteMessage', [
'chat_id'=> $cid, 
'message_id'=> $mid,
]);
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"🔵 <b>Kechirasiz,</b> <a href='tg://user?id=$uid'>$name</a> <code>$title</code> <b>guruhida yozish uchun</b> @$user <b>kanaliga a'zo bo'lishingiz kerak!</b>",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>$tit, 'url'=>"https://t.me/".$user]],
]
])
]);
}
}
}
}

// Admin panel
if ($tx == "/send" and $cid == $admin){
bot ('sendmessage', [
'chat_id'=> $cid,
'text'=>"Xabar matnini yuboring:",
'reply_markup'=>json_encode([
'force_reply'=>true 
])
]);
}

if ($reply == "Xabar matnini yuboring:" and $cid == $admin){
$ex = explode("\n", $guruh);
foreach($ex as $for){
$ok = bot ('sendmessage', [
'chat_id'=> $for,
'text'=>$tx,
'parse_mode'=>"html",
'disable_notification'=>true
]);
$send = $ok->ok;
if($send){
$true = file_get_contents("bot/send.ok");
file_put_contents("bot/send.ok","$true\n$f");
}
}
$true = file_get_contents("bot/send.ok");
$truecount = substr_count($true,"\n");
bot ('sendmessage', [
'chat_id'=> $admin,
'text'=>"$truecount guruhga xabar yetkazildi",
]);
unlink("bot/send.ok");
}

if ($tx == "/sendu" and $cid == $admin){
bot ('sendmessage', [
'chat_id'=> $cid,
'text'=>"Xabar matnini kiriting:",
'reply_markup'=>json_encode([
'force_reply'=>true 
])
]);
}

if ($reply == "Xabar matnini kiriting:" and $cid == $admin){
$ex = explode("\n", $user);
foreach($ex as $f){
$ok = bot ('sendmessage', [
'chat_id'=> $f,
'text'=>$tx,
'parse_mode'=>"html",
'disable_notification'=>true
]);
$send = $ok->ok;
if($send){
$true = file_get_contents("bot/send.ok");
file_put_contents("bot/send.ok","$true\n$f");
}
}
$true = file_get_contents("bot/send.ok");
$truecount = substr_count($true,"\n");
bot ('sendmessage', [
'chat_id'=> $admin,
'text'=>"$truecount kishiga xabar yetkazildi",
]);
unlink("bot/send.ok");
}

?>