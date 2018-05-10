//배너 전체 개수 담는 변수 num선언
var num = 0;
//현재 보이는 배너 인덱스값
var nCurrentBannerIndex = 0;
//자동재생 변수 선언
var autoTimer;


$(document).ready(function(){
	init();
	initEventListener();
	startAutoPlay();
})


function init(){
	//imgBox의 가로크기(이미지가로크기460px*이미지갯수num) 설정
	num = $('.imgBox').children('img').length;
	$('.imgBox').width(460*num);
	//배너 첫 번째 버튼 활성화
	showBannerDotAt(0);
}

//이벤트 처리
function initEventListener(){
	//배너 버튼에 마우스 올리면 해당 배너 보이기
	$('.banner_btn ul li a').bind('mouseenter',function(){
		var k = $('.banner_btn ul li a').index(this);
		//var k = $(this).index();
		//console.log(k);
		showBannerAt(k);
	})
	//배너에 마우스 올리면 자동실행 멈춤
	$('.banner_slider').bind('mouseenter',function(){
		stopAutoPlay();
	})
	//배너에 마우스 내리면 자동실행 재생
	$('.banner_slider').bind('mouseleave',function(){
		startAutoPlay();
	})
}



//배너 슬라이드
function showBannerAt(nIndex){
	if(nIndex != nCurrentBannerIndex){
		var nPosition = -460*nIndex;
		$('.imgBox').stop().animate({left:nPosition},1000);
		//해당 배너에 맞는 버튼 활성화
		this.showBannerDotAt(nIndex);
		//현재 보이는 배너 인덱스 업데이트 
		this.nCurrentBannerIndex = nIndex;
	}
}


//배너 버튼 
function showBannerDotAt(nIndex){
	$('.banner_btn ul li a').eq(this.nCurrentBannerIndex).removeClass('select');
	$('.banner_btn ul li a').eq(nIndex).addClass('select');
}

//자동실행
function startAutoPlay(){
	autoTimer = setInterval(function(){
		nextBanner();
	},4000);
}

function nextBanner(){
	var nIndex = this.nCurrentBannerIndex + 1;
	if(nIndex>=num){
		nIndex = 0;
	}
	this.showBannerAt(nIndex);
}


//자동실행 멈춤 
function stopAutoPlay(){
	clearInterval(this.autoTimer);
}


