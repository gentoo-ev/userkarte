//Pop up information box II (Mike McGrath (mike_mcgrath@lineone.net,  http://website.lineone.net/~mike_mcgrath))

Xoffset= -205;    // modify these values to ...
Yoffset= 20;    // change the popup position.

var old,skn,iex=(document.all),yyy=-1000,xxx=-1000;

var ns4=document.layers
var ns6=document.getElementById&&!document.all
var ie4=document.all

if (ns4)
     skn=document.dek
else if (ns6)
     skn=document.getElementById("dek").style
else if (ie4)
     skn=document.all.dek.style
if(ns4)document.captureEvents(Event.MOUSEMOVE);
else{
  skn.visibility="visible"
    skn.display="none"
    }
document.onmousemove=get_mouse;

function popup(msg,Xoffset2,Yoffset2){
  //var content="<table width='150' border='1' align='left' bgcolor='#cccccc'><td class='nav'><font size='-1'><tr><ul style='margin:0px;margin-left:-20px'>"+msg+"</ul></font></td></tr></table>";
  var content="<table width='150' border='1' align='left' bgcolor='#cccccc'><td class='nav'><font size='-1'>"+msg+"</font></td></tr></table>";
  yyy=Yoffset2;
  xxx=Xoffset2;
  if(ns4){skn.document.write(content);skn.document.close();skn.visibility="visible"}
  if(ns6){document.getElementById("dek").innerHTML=content;skn.display=''}
  if(ie4){document.all("dek").innerHTML=content;skn.display=''}
//  alert(content);

}

function get_mouse(e){
  var x=(ns4||ns6)?e.pageX:event.x+document.body.scrollLeft;
  skn.left=x+xxx;
  var y=(ns4||ns6)?e.pageY:event.y+document.body.scrollTop;
  skn.top=y+yyy;
}

function kill(){
  yyy=-1000;
  if(ns4) {
	skn.visibility="hidden";
  } else if (ns6||ie4)
  {
    skn.display="none";
  }
}
