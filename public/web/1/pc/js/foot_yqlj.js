// JavaScript Document
marqueesHeight=28; 

stopscroll=false; 

with(icefable1) 
{ 
style.height=marqueesHeight; 
style.overflowX="visible"; 

style.overflowY="hidden"; 

noWrap=true; 

onmouseover=new Function("stopscroll=true"); 

onmouseout=new Function("stopscroll=false"); 

} 

preTop=0; 
currentTop=25; 
stoptime=0; 

icefable1.innerHTML += icefable1.innerHTML; 



function init_srolltext() 
{ 
icefable1.scrollTop=0; 
setInterval("scrollUp()",15); 

} 



function scrollUp()  
{ 

if(stopscroll==true) return; 

currentTop+=1; 

if(currentTop>25) 

    { 

    stoptime+=1; 

    currentTop-=1; 

        if(stoptime==200) 
     
        { 
         
        currentTop=0; 
  
        stoptime=0; 

        } 

    } 
  
    else 
    { 
  
    preTop=icefable1.scrollTop; 
    icefable1.scrollTop+=1; 
     

      if(preTop==icefable1.scrollTop) 
   
          { 
   
          icefable1.scrollTop=0; 

          icefable1.scrollTop+=1; 

        } 
  
    } 

} 
init_srolltext(); 