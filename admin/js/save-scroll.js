/*
Copyright (c) 2008-2012, www.lampkin.net All rights reserved.
Code licensed under the BSD License: http://www.lampkin.net/license/
http://www.lampkin.net
Version 1.0.0
June 10, 2020.
*/
	$(document).ready(function (e) {
    let UrlsObj = localStorage.getItem('rememberScroll');
    let ParseUrlsObj = JSON.parse(UrlsObj);
    let windowUrl = window.location.href;

    if (ParseUrlsObj == null) {
        return false;
    }

    ParseUrlsObj.forEach(function (el) {
        if (el.url === windowUrl) {
            let getPos = el.scroll;
            $(window).scrollTop(getPos);
        }
    });

});

function RememberScrollPage(scrollPos) {

    let UrlsObj = localStorage.getItem('rememberScroll');
    let urlsArr = JSON.parse(UrlsObj);

    if (urlsArr == null) {
        urlsArr = [];
    }

    if (urlsArr.length == 0) {
        urlsArr = [];
    }

    let urlWindow = window.location.href;
    let urlScroll = scrollPos;
    let urlObj = {url: urlWindow, scroll: scrollPos};
    let matchedUrl = false;
    let matchedIndex = 0;

    if (urlsArr.length != 0) {
        urlsArr.forEach(function (el, index) {

            if (el.url === urlWindow) {
                matchedUrl = true;
                matchedIndex = index;
            }

        });

        if (matchedUrl === true) {
            urlsArr[matchedIndex].scroll = urlScroll;
        } else {
            urlsArr.push(urlObj);
        }


    } else {
        urlsArr.push(urlObj);
    }

    localStorage.setItem('rememberScroll', JSON.stringify(urlsArr));

}

$(window).scroll(function (event) {
    let topScroll = $(window).scrollTop();
    // console.log('Scrolling', topScroll);
    RememberScrollPage(topScroll);
});
