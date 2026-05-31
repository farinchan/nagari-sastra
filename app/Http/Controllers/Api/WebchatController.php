<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WebchatConversation;
use App\Models\WebchatMessage;
use App\Models\WebchatWidget;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebchatController extends Controller
{
    /**
     * Serve the embeddable widget JavaScript.
     * Usage: <script src="https://yoursite.com/api/webchat/embed.js?token=xxx"></script>
     */
    public function embedScript(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            return response('// Webchat: missing token', 200)
                ->header('Content-Type', 'application/javascript');
        }

        $widget = WebchatWidget::where('token', $token)->where('is_active', true)->first();
        if (!$widget) {
            return response('// Webchat: invalid or inactive widget token', 200)
                ->header('Content-Type', 'application/javascript');
        }

        $apiBase = url('/api/webchat');
        $c1 = e($widget->primary_color);
        $title = addslashes(e($widget->header_title));
        $subtitle = addslashes(e($widget->header_subtitle));
        $widgetToken = e($widget->token);

        // Build CSS as a clean PHP string — no heredoc/nowdoc nesting issues
        $css = '#_wc_root{position:fixed!important;bottom:20px!important;right:20px!important;z-index:2147483647!important;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,sans-serif!important;font-size:14px!important;line-height:1.5!important;color:#1f2937!important;direction:ltr!important;text-align:left!important;letter-spacing:normal!important;text-transform:none!important;text-decoration:none!important;visibility:visible!important;opacity:1!important;pointer-events:auto!important}';

        // FAB button
        $css .= '#_wc_root #_wc_fab{width:56px!important;height:56px!important;border-radius:28px!important;background:'.$c1.'!important;border:none!important;cursor:pointer!important;box-shadow:0 4px 14px rgba(0,0,0,.22)!important;display:flex!important;align-items:center!important;justify-content:center!important;transition:box-shadow .2s,transform .15s!important;position:relative!important;outline:none!important;padding:0!important;margin:0!important;float:none!important;text-indent:0!important;min-width:0!important;min-height:0!important;max-width:none!important;max-height:none!important;overflow:visible!important;opacity:1!important}';
        $css .= '#_wc_root #_wc_fab:hover{box-shadow:0 6px 20px rgba(0,0,0,.3)!important;transform:translateY(-2px)!important}';
        $css .= '#_wc_root #_wc_fab:active{transform:scale(.94)!important}';
        $css .= '#_wc_root #_wc_fab svg{width:26px!important;height:26px!important;fill:#fff!important;display:block!important;margin:0!important;padding:0!important;border:none!important;float:none!important}';
        $css .= '#_wc_root #_wc_fab.active ._wci_chat{display:none!important}';
        $css .= '#_wc_root #_wc_fab.active ._wci_x{display:block!important}';
        $css .= '#_wc_root #_wc_fab:not(.active) ._wci_chat{display:block!important}';
        $css .= '#_wc_root #_wc_fab:not(.active) ._wci_x{display:none!important}';

        // Badge
        $css .= '#_wc_root #_wc_badge{position:absolute!important;top:-3px!important;right:-3px!important;min-width:20px!important;height:20px!important;border-radius:10px!important;background:#ef4444!important;color:#fff!important;font-size:11px!important;font-weight:700!important;font-family:inherit!important;display:none!important;align-items:center!important;justify-content:center!important;padding:0 5px!important;line-height:20px!important;border:2px solid #fff!important;margin:0!important;text-align:center!important;box-sizing:border-box!important}';

        // Window
        $css .= '#_wc_root #_wc_win{position:absolute!important;bottom:66px!important;right:0!important;width:360px!important;max-height:500px!important;background:#fff!important;border-radius:12px!important;box-shadow:0 8px 30px rgba(0,0,0,.16),0 0 0 1px rgba(0,0,0,.05)!important;overflow:hidden!important;display:none!important;flex-direction:column!important;margin:0!important;padding:0!important;border:none!important;float:none!important;opacity:1!important;transform:none!important;visibility:visible!important;box-sizing:border-box!important}';
        $css .= '#_wc_root #_wc_win.open{display:flex!important;animation:_wcOpen .25s ease-out!important}';
        $css .= '@keyframes _wcOpen{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}';

        // Header
        $css .= '#_wc_root ._wc_hd{background:'.$c1.'!important;padding:16px 18px!important;display:flex!important;align-items:center!important;gap:10px!important;flex-shrink:0!important;margin:0!important;border:none!important;border-radius:0!important;box-sizing:border-box!important;float:none!important}';
        $css .= '#_wc_root ._wc_hd_av{width:38px!important;height:38px!important;border-radius:19px!important;background:rgba(255,255,255,.18)!important;display:flex!important;align-items:center!important;justify-content:center!important;flex-shrink:0!important;margin:0!important;padding:0!important;border:none!important;box-sizing:border-box!important}';
        $css .= '#_wc_root ._wc_hd_av svg{width:20px!important;height:20px!important;fill:#fff!important;display:block!important;margin:0!important;padding:0!important;border:none!important}';
        $css .= '#_wc_root ._wc_hd_t{font-size:15px!important;font-weight:600!important;color:#fff!important;margin:0 0 1px!important;padding:0!important;line-height:1.3!important;display:block!important;font-family:inherit!important;border:none!important;background:none!important;text-transform:none!important;letter-spacing:normal!important;text-decoration:none!important}';
        $css .= '#_wc_root ._wc_hd_s{font-size:12px!important;color:rgba(255,255,255,.8)!important;margin:0!important;padding:0!important;line-height:1.3!important;display:flex!important;align-items:center!important;gap:5px!important;font-family:inherit!important;border:none!important;background:none!important;font-weight:400!important;text-transform:none!important;letter-spacing:normal!important;text-decoration:none!important}';
        $css .= '#_wc_root ._wc_dot{width:7px!important;height:7px!important;border-radius:50%!important;background:#34d399!important;display:inline-block!important;margin:0!important;padding:0!important;flex-shrink:0!important;border:none!important}';

        // Message body
        $css .= '#_wc_root ._wc_body{flex:1 1 auto!important;overflow-y:auto!important;padding:14px!important;background:#f3f4f6!important;min-height:240px!important;max-height:300px!important;margin:0!important;border:none!important;box-sizing:border-box!important;display:block!important}';
        $css .= '#_wc_root ._wc_body::-webkit-scrollbar{width:4px!important}';
        $css .= '#_wc_root ._wc_body::-webkit-scrollbar-track{background:transparent!important}';
        $css .= '#_wc_root ._wc_body::-webkit-scrollbar-thumb{background:#d1d5db!important;border-radius:4px!important}';

        // Messages
        $css .= '#_wc_root ._wc_m{display:flex!important;margin:0 0 8px!important;padding:0!important;animation:_wcFadeIn .2s ease!important;border:none!important;background:none!important;float:none!important;box-sizing:border-box!important}';
        $css .= '@keyframes _wcFadeIn{from{opacity:0;transform:translateY(4px)}to{opacity:1;transform:translateY(0)}}';
        $css .= '#_wc_root ._wc_m.v{justify-content:flex-end!important}';
        $css .= '#_wc_root ._wc_m.a{justify-content:flex-start!important}';
        $css .= '#_wc_root ._wc_m_w{max-width:76%!important;margin:0!important;padding:0!important;border:none!important;background:none!important;box-sizing:border-box!important}';
        $css .= '#_wc_root ._wc_b{padding:9px 13px!important;border-radius:12px!important;font-size:13px!important;line-height:1.45!important;word-wrap:break-word!important;word-break:break-word!important;margin:0!important;display:block!important;font-family:inherit!important;letter-spacing:normal!important;text-transform:none!important;text-decoration:none!important;text-align:left!important;box-sizing:border-box!important;float:none!important}';
        $css .= '#_wc_root ._wc_m.v ._wc_b{background:'.$c1.'!important;color:#fff!important;border:none!important;border-bottom-right-radius:3px!important}';
        $css .= '#_wc_root ._wc_m.a ._wc_b{background:#fff!important;color:#1f2937!important;border:1px solid #e5e7eb!important;border-bottom-left-radius:3px!important}';
        $css .= '#_wc_root ._wc_t{font-size:10px!important;color:#9ca3af!important;margin:3px 4px 0!important;padding:0!important;display:block!important;line-height:1.2!important;font-family:inherit!important;border:none!important;background:none!important;text-decoration:none!important}';
        $css .= '#_wc_root ._wc_m.v ._wc_t{text-align:right!important}';
        $css .= '#_wc_root ._wc_m.a ._wc_t{text-align:left!important}';

        // Footer input
        $css .= '#_wc_root ._wc_ft{padding:10px 14px!important;border-top:1px solid #e5e7eb!important;display:flex!important;gap:8px!important;align-items:flex-end!important;background:#fff!important;flex-shrink:0!important;margin:0!important;border-bottom:none!important;border-left:none!important;border-right:none!important;box-sizing:border-box!important;float:none!important}';
        $css .= '#_wc_root ._wc_ft textarea{flex:1 1 0%!important;border:1px solid #d1d5db!important;border-radius:8px!important;padding:8px 12px!important;font-size:13px!important;font-family:inherit!important;resize:none!important;outline:none!important;min-height:38px!important;max-height:76px!important;line-height:1.4!important;color:#1f2937!important;background:#f9fafb!important;margin:0!important;transition:border-color .15s!important;display:block!important;-webkit-appearance:none!important;appearance:none!important;box-sizing:border-box!important;width:auto!important;float:none!important;box-shadow:none!important;text-transform:none!important;letter-spacing:normal!important}';
        $css .= '#_wc_root ._wc_ft textarea:focus{border-color:'.$c1.'!important;background:#fff!important}';
        $css .= '#_wc_root ._wc_ft textarea::placeholder{color:#9ca3af!important}';
        $css .= '#_wc_root ._wc_ft button{width:38px!important;height:38px!important;border-radius:8px!important;background:'.$c1.'!important;border:none!important;cursor:pointer!important;display:flex!important;align-items:center!important;justify-content:center!important;flex-shrink:0!important;padding:0!important;margin:0!important;transition:opacity .15s!important;outline:none!important;box-sizing:border-box!important;float:none!important;min-width:0!important;box-shadow:none!important}';
        $css .= '#_wc_root ._wc_ft button:hover{opacity:.85!important}';
        $css .= '#_wc_root ._wc_ft button:disabled{opacity:.4!important;cursor:not-allowed!important}';
        $css .= '#_wc_root ._wc_ft button svg{width:18px!important;height:18px!important;fill:#fff!important;display:block!important;margin:0!important;padding:0!important;border:none!important}';

        // Prechat form
        $css .= '#_wc_root ._wc_pre{padding:24px 20px!important;background:#fff!important;flex:1 1 auto!important;display:flex!important;flex-direction:column!important;justify-content:center!important;margin:0!important;border:none!important;box-sizing:border-box!important;float:none!important}';
        $css .= '#_wc_root ._wc_pre_h{font-size:16px!important;font-weight:600!important;color:#111827!important;margin:0 0 4px!important;padding:0!important;line-height:1.4!important;display:block!important;font-family:inherit!important;border:none!important;background:none!important;text-transform:none!important;letter-spacing:normal!important;text-decoration:none!important;text-align:left!important}';
        $css .= '#_wc_root ._wc_pre_p{font-size:13px!important;color:#6b7280!important;margin:0 0 18px!important;padding:0!important;line-height:1.5!important;display:block!important;font-family:inherit!important;border:none!important;background:none!important;text-transform:none!important;letter-spacing:normal!important;text-decoration:none!important;font-weight:400!important;text-align:left!important}';
        $css .= '#_wc_root ._wc_pre input{width:100%!important;padding:10px 12px!important;border:1px solid #d1d5db!important;border-radius:8px!important;font-size:13px!important;font-family:inherit!important;outline:none!important;margin:0 0 10px!important;color:#1f2937!important;background:#fff!important;transition:border-color .15s!important;display:block!important;-webkit-appearance:none!important;appearance:none!important;line-height:1.4!important;box-sizing:border-box!important;float:none!important;height:auto!important;box-shadow:none!important;text-transform:none!important;letter-spacing:normal!important}';
        $css .= '#_wc_root ._wc_pre input:focus{border-color:'.$c1.'!important}';
        $css .= '#_wc_root ._wc_pre input::placeholder{color:#9ca3af!important}';
        $css .= '#_wc_root ._wc_pre_btn{width:100%!important;padding:10px 16px!important;border:none!important;border-radius:8px!important;background:'.$c1.'!important;color:#fff!important;font-size:14px!important;font-weight:600!important;font-family:inherit!important;cursor:pointer!important;margin:4px 0 0!important;transition:opacity .15s!important;display:block!important;text-align:center!important;line-height:1.4!important;outline:none!important;-webkit-appearance:none!important;appearance:none!important;box-sizing:border-box!important;float:none!important;height:auto!important;box-shadow:none!important;text-transform:none!important;letter-spacing:normal!important;text-decoration:none!important}';
        $css .= '#_wc_root ._wc_pre_btn:hover{opacity:.9!important}';
        $css .= '#_wc_root ._wc_pre_btn:disabled{opacity:.5!important;cursor:not-allowed!important}';

        // Powered
        $css .= '#_wc_root ._wc_pw{text-align:center!important;padding:5px!important;font-size:10px!important;color:#9ca3af!important;background:#fff!important;border-top:1px solid #f0f0f0!important;margin:0!important;line-height:1.4!important;display:block!important;font-family:inherit!important;border-bottom:none!important;border-left:none!important;border-right:none!important;box-sizing:border-box!important;font-weight:400!important;text-transform:none!important;letter-spacing:normal!important;text-decoration:none!important}';

        // Responsive
        $css .= '@media(max-width:480px){#_wc_root #_wc_win{width:calc(100vw - 20px)!important;right:-10px!important;bottom:64px!important;max-height:70vh!important}#_wc_root{bottom:14px!important;right:14px!important}}';

        // Image styles
        $css .= '#_wc_root ._wc_img{max-width:200px!important;max-height:160px!important;border-radius:8px!important;display:block!important;margin:0 0 4px!important;cursor:pointer!important;object-fit:cover!important;border:none!important}';
        $css .= '#_wc_root ._wc_att{width:34px!important;height:38px!important;border:none!important;background:none!important;cursor:pointer!important;display:flex!important;align-items:center!important;justify-content:center!important;flex-shrink:0!important;padding:0!important;margin:0!important;outline:none!important;opacity:.5!important;transition:opacity .15s!important}';
        $css .= '#_wc_root ._wc_att:hover{opacity:.8!important}';
        $css .= '#_wc_root ._wc_att svg{width:20px!important;height:20px!important;fill:#6b7280!important;display:block!important;margin:0!important;padding:0!important;border:none!important}';
        $css .= '#_wc_root ._wc_pv{padding:6px 14px!important;border-top:1px solid #e5e7eb!important;background:#f9fafb!important;display:none!important;align-items:center!important;gap:8px!important;flex-shrink:0!important;margin:0!important;box-sizing:border-box!important}';
        $css .= '#_wc_root ._wc_pv.show{display:flex!important}';
        $css .= '#_wc_root ._wc_pv img{height:44px!important;border-radius:6px!important;object-fit:cover!important;display:block!important;margin:0!important;border:none!important}';
        $css .= '#_wc_root ._wc_pv_x{width:20px!important;height:20px!important;border-radius:10px!important;background:#ef4444!important;border:none!important;cursor:pointer!important;display:flex!important;align-items:center!important;justify-content:center!important;padding:0!important;margin:0!important;flex-shrink:0!important;outline:none!important}';
        $css .= '#_wc_root ._wc_pv_x svg{width:12px!important;height:12px!important;fill:#fff!important;display:block!important;margin:0!important;border:none!important}';
        $css .= '#_wc_root ._wc_fi{display:none!important}';

        $cssEscaped = addslashes($css);

        $js = <<<JSWIDGET
(function(){
'use strict';
if(document.getElementById('_wc_root')) return;

var s=document.createElement('style');
s.setAttribute('data-webchat','1');
s.textContent='{$cssEscaped}';
document.head.appendChild(s);

var root = document.createElement('div');
root.id = '_wc_root';
root.innerHTML =
'<div id="_wc_win">' +
  '<div class="_wc_hd">' +
    '<div class="_wc_hd_av"><svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg></div>' +
    '<div><span class="_wc_hd_t">{$title}</span><span class="_wc_hd_s"><span class="_wc_dot"></span>{$subtitle}</span></div>' +
  '</div>' +
  '<div class="_wc_pre" id="_wc_pre">' +
    '<span class="_wc_pre_h">Halo! \\uD83D\\uDC4B</span>' +
    '<span class="_wc_pre_p">Silakan isi nama Anda untuk memulai percakapan dengan tim kami.</span>' +
    '<input type="text" id="_wc_nm" placeholder="Nama Anda" autocomplete="off"/>' +
    '<input type="email" id="_wc_em" placeholder="Email (opsional)" autocomplete="off"/>' +
    '<button type="button" class="_wc_pre_btn" id="_wc_go">Mulai Chat</button>' +
  '</div>' +
  '<div class="_wc_body" id="_wc_body" style="display:none !important"></div>'+
  '<div class="_wc_pv" id="_wc_pv"><img id="_wc_pvimg" src=""/><button type="button" class="_wc_pv_x" id="_wc_pvx"><svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg></button></div>'+
  '<div class="_wc_ft" id="_wc_ft" style="display:none !important">'+
    '<input type="file" id="_wc_fi" class="_wc_fi" accept="image/jpeg,image/png,image/gif,image/webp"/>'+
    '<button type="button" class="_wc_att" id="_wc_att" title="Kirim Gambar"><svg viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg></button>'+
    '<textarea id="_wc_txt" placeholder="Ketik pesan..." rows="1"></textarea>'+
    '<button type="button" id="_wc_snd"><svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg></button>'+
  '</div>'+
  '<span class="_wc_pw">Powered by Webchat</span>' +
'</div>' +
'<button id="_wc_fab" aria-label="Chat">' +
  '<svg class="_wci_chat" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12z"/><path d="M7 9h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2z"/></svg>' +
  '<svg class="_wci_x" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>' +
  '<span id="_wc_badge">0</span>' +
'</button>';
document.body.appendChild(root);

var API='{$apiBase}',TK='{$widgetToken}',SK='wc_s_'+TK;
var sid=localStorage.getItem(SK),lid=0,pll=null,opn=false,on=false,ur=0;
var fab=document.getElementById('_wc_fab');
var win=document.getElementById('_wc_win');
var body=document.getElementById('_wc_body');
var ft=document.getElementById('_wc_ft');
var pre=document.getElementById('_wc_pre');
var txt=document.getElementById('_wc_txt');
var snd=document.getElementById('_wc_snd');
var go=document.getElementById('_wc_go');
var bdg=document.getElementById('_wc_badge');
var nm=document.getElementById('_wc_nm');
var em=document.getElementById('_wc_em');
var att=document.getElementById('_wc_att');
var fi=document.getElementById('_wc_fi');
var pv=document.getElementById('_wc_pv');
var pvimg=document.getElementById('_wc_pvimg');
var pvx=document.getElementById('_wc_pvx');
var selFile=null;

fab.addEventListener('click',function(){
  opn=!opn;
  if(opn){win.classList.add('open');fab.classList.add('active');ur=0;uBdg();if(on){sB();txt.focus();}}
  else{win.classList.remove('open');fab.classList.remove('active');}
});

go.addEventListener('click',function(){
  var n=nm.value.trim();
  if(!n){nm.style.borderColor='#ef4444';nm.focus();return;}
  nm.style.borderColor='';doStart(n,em.value.trim());
});
nm.addEventListener('keydown',function(e){if(e.key==='Enter'){e.preventDefault();go.click();}});
em.addEventListener('keydown',function(e){if(e.key==='Enter'){e.preventDefault();go.click();}});

function doStart(n,e){
  go.disabled=true;go.textContent='Memulai...';
  rq(API+'/start',{session_id:sid,token:TK,visitor_name:n,visitor_email:e})
  .then(function(d){
    if(d.success){
      sid=d.session_id;localStorage.setItem(SK,sid);
      if(d.messages.length<=1&&n)sInfo(n,e);
      pre.style.setProperty('display','none','important');
      body.style.setProperty('display','block','important');
      ft.style.setProperty('display','flex','important');
      on=true;body.innerHTML='';
      d.messages.forEach(function(m){aMsg(m);});
      sB();poll();txt.focus();
    }
  }).catch(function(){go.disabled=false;go.textContent='Mulai Chat';});
}

function sInfo(n,e){
  var m='Halo, nama saya '+n;if(e)m+=' ('+e+')';
  rq(API+'/send',{session_id:sid,token:TK,message:m,visitor_name:n,visitor_email:e})
  .then(function(d){if(d.success){aMsg(d.message);lid=d.message.id;sB();}});
}

function send(){
  var m=txt.value.trim();
  if(!m&&!selFile)return;
  snd.disabled=true;
  if(selFile){
    var fd=new FormData();
    fd.append('session_id',sid);
    fd.append('token',TK);
    fd.append('image',selFile);
    if(m)fd.append('message',m);
    fetch(API+'/upload',{method:'POST',body:fd})
    .then(function(r){return r.json();})
    .then(function(d){if(d.success){aMsg(d.message);lid=d.message.id;sB();}snd.disabled=false;txt.value='';ar();clrFile();})
    .catch(function(){snd.disabled=false;});
  }else{
    txt.value='';ar();
    rq(API+'/send',{session_id:sid,token:TK,message:m})
    .then(function(d){if(d.success){aMsg(d.message);lid=d.message.id;sB();}snd.disabled=false;})
    .catch(function(){snd.disabled=false;});
  }
}
snd.addEventListener('click',send);
txt.addEventListener('keydown',function(e){if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();send();}});
function ar(){txt.style.height='auto';txt.style.height=Math.min(txt.scrollHeight,76)+'px';}
txt.addEventListener('input',ar);

att.addEventListener('click',function(){fi.click();});
fi.addEventListener('change',function(){
  var f=fi.files[0];if(!f)return;
  if(f.size>5*1024*1024){fi.value='';return;}
  selFile=f;
  var r=new FileReader();
  r.onload=function(ev){pvimg.src=ev.target.result;pv.classList.add('show');};
  r.readAsDataURL(f);
});
pvx.addEventListener('click',function(){clrFile();});
function clrFile(){selFile=null;fi.value='';pv.classList.remove('show');pvimg.src='';}

function aMsg(m){
  if(document.querySelector('[data-wcid="'+m.id+'"]'))return;
  var d=document.createElement('div');
  d.className='_wc_m '+(m.sender==='visitor'?'v':'a');
  d.setAttribute('data-wcid',m.id);
  var inner='';
  if(m.image)inner+='<a href="'+m.image+'" target="_blank" rel="noopener"><img class="_wc_img" src="'+m.image+'" alt=""/></a>';
  if(m.message)inner+='<span class="_wc_b">'+esc(m.message).replace(/\\n/g,'<br>')+'</span>';
  inner+='<span class="_wc_t">'+m.time+'</span>';
  d.innerHTML='<div class="_wc_m_w">'+inner+'</div>';
  body.appendChild(d);if(m.id>lid)lid=m.id;
}
function esc(s){var d=document.createElement('div');d.textContent=s;return d.innerHTML;}
function sB(){if(body)body.scrollTop=body.scrollHeight;}
function uBdg(){if(ur>0){bdg.textContent=ur>9?'9+':ur;bdg.style.setProperty('display','flex','important');}else{bdg.style.setProperty('display','none','important');}}

function poll(){
  if(pll)clearInterval(pll);
  pll=setInterval(function(){
    if(!sid)return;
    rq(API+'/fetch',{session_id:sid,token:TK,last_id:lid})
    .then(function(d){
      if(d.success&&d.messages.length>0){
        d.messages.forEach(function(m){
          if(m.id>lid){aMsg(m);lid=m.id;if(m.sender==='admin'&&!opn){ur++;uBdg();}}
        });sB();
      }
    });
  },4000);
}

function rq(u,b){
  return fetch(u,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(b)}).then(function(r){return r.json();});
}

if(sid){
  rq(API+'/start',{session_id:sid,token:TK})
  .then(function(d){
    if(d.success&&d.messages.length>0){
      sid=d.session_id;localStorage.setItem(SK,sid);
      pre.style.setProperty('display','none','important');
      body.style.setProperty('display','block','important');
      ft.style.setProperty('display','flex','important');
      on=true;body.innerHTML='';
      d.messages.forEach(function(m){aMsg(m);});
      sB();poll();
    }
  });
}
})();
JSWIDGET;

        return response($js, 200)
            ->header('Content-Type', 'application/javascript')
            ->header('Cache-Control', 'public, max-age=300');
    }

    /**
     * Start or resume a conversation.
     */
    public function startConversation(Request $request)
    {
        $token = $request->input('token');
        $widget = $token ? WebchatWidget::where('token', $token)->where('is_active', true)->first() : null;

        $sessionId = $request->input('session_id');
        $conversation = null;

        if ($sessionId) {
            $conversation = WebchatConversation::where('session_id', $sessionId)->first();
        }

        if (!$conversation) {
            $sessionId = 'wc_' . Str::random(32);
            $conversation = WebchatConversation::create([
                'session_id' => $sessionId,
                'webchat_widget_id' => $widget ? $widget->id : null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'active',
            ]);

            // Update visitor info if provided
            if ($request->visitor_name) {
                $conversation->update(['visitor_name' => $request->visitor_name]);
            }
            if ($request->visitor_email) {
                $conversation->update(['visitor_email' => $request->visitor_email]);
            }

            $greeting = $widget ? $widget->greeting_message : 'Halo! 👋 Selamat datang. Ada yang bisa kami bantu?';
            WebchatMessage::create([
                'webchat_conversation_id' => $conversation->id,
                'sender' => 'admin',
                'message' => $greeting,
                'is_read' => false,
            ]);

            $conversation->update(['last_message_at' => now()]);
        }

        $messages = $conversation->messagesAsc()->get()->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender' => $msg->sender,
                'message' => $msg->message,
                'image' => $msg->image ? asset('storage/' . $msg->image) : null,
                'time' => $msg->created_at->format('H:i'),
                'date' => $msg->created_at->format('d M Y'),
            ];
        });

        return response()->json([
            'success' => true,
            'session_id' => $conversation->session_id,
            'conversation_id' => $conversation->id,
            'messages' => $messages,
        ]);
    }

    /**
     * Visitor sends a message.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'message' => 'nullable|string|max:2000',
            'visitor_name' => 'nullable|string|max:255',
            'visitor_email' => 'nullable|email|max:255',
        ]);

        $conversation = WebchatConversation::where('session_id', $request->session_id)->first();

        if (!$conversation) {
            return response()->json(['success' => false, 'error' => 'Conversation not found'], 404);
        }

        if ($request->visitor_name && !$conversation->visitor_name) {
            $conversation->update(['visitor_name' => $request->visitor_name]);
        }
        if ($request->visitor_email && !$conversation->visitor_email) {
            $conversation->update(['visitor_email' => $request->visitor_email]);
        }

        $message = WebchatMessage::create([
            'webchat_conversation_id' => $conversation->id,
            'sender' => 'visitor',
            'message' => $request->input('message', ''),
            'is_read' => false,
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender' => $message->sender,
                'message' => $message->message,
                'image' => $message->image ? asset('storage/' . $message->image) : null,
                'time' => $message->created_at->format('H:i'),
                'date' => $message->created_at->format('d M Y'),
            ],
        ]);
    }

    /**
     * Fetch new messages for polling (visitor side).
     */
    public function fetchMessages(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'last_id' => 'nullable|integer',
        ]);

        $conversation = WebchatConversation::where('session_id', $request->session_id)->first();

        if (!$conversation) {
            return response()->json(['success' => false, 'error' => 'Conversation not found'], 404);
        }

        $query = $conversation->messagesAsc();
        if ($request->last_id) {
            $query->where('id', '>', $request->last_id);
        }

        $messages = $query->get()->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender' => $msg->sender,
                'message' => $msg->message,
                'image' => $msg->image ? asset('storage/' . $msg->image) : null,
                'time' => $msg->created_at->format('H:i'),
                'date' => $msg->created_at->format('d M Y'),
            ];
        });

        $conversation->messages()
            ->where('sender', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Upload image for webchat message.
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,gif,webp|max:5120',
            'message' => 'nullable|string|max:2000',
        ]);

        $conversation = WebchatConversation::where('session_id', $request->session_id)->first();
        if (!$conversation) {
            return response()->json(['success' => false, 'error' => 'Conversation not found'], 404);
        }

        $file = $request->file('image');

        // Double-check MIME from file content (not extension)
        $realMime = $file->getMimeType();
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($realMime, $allowed)) {
            return response()->json(['success' => false, 'error' => 'Invalid image type'], 422);
        }

        // Verify it's a real image
        $imageInfo = @getimagesize($file->getPathname());
        if ($imageInfo === false) {
            return response()->json(['success' => false, 'error' => 'Corrupted image file'], 422);
        }

        // Store with random hash filename (prevents path traversal & filename guessing)
        $ext = $file->guessExtension() ?: 'jpg';
        $filename = bin2hex(random_bytes(16)) . '.' . $ext;
        $path = $file->storeAs('webchat', $filename, 'public');

        $message = WebchatMessage::create([
            'webchat_conversation_id' => $conversation->id,
            'sender' => 'visitor',
            'message' => $request->input('message', ''),
            'image' => $path,
            'is_read' => false,
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender' => $message->sender,
                'message' => $message->message,
                'image' => asset('storage/' . $path),
                'time' => $message->created_at->format('H:i'),
                'date' => $message->created_at->format('d M Y'),
            ],
        ]);
    }
}
