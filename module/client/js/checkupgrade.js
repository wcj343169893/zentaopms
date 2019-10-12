/** https://github.com/omichelsen/compare-versions/ The MIT License (MIT) Copyright (c) 2015-2017 Ole Michelsen*/
!function(a,b){"use strict";function d(a,b){return-1===a.indexOf(b)?a.length:a.indexOf(b)}function e(a){var b=a.replace(/^v/,"").replace(/\+.*$/,""),c=d(b,"-"),e=b.substring(0,c).split(".");return e.push(b.substring(c+1)),e}function f(a){return isNaN(Number(a))?a:Number(a)}function g(a){if("string"!=typeof a)throw new TypeError("Invalid argument expected string");if(!c.test(a))throw new Error("Invalid argument not valid semver ('"+a+"' received)")}function h(a,c){var d,h,i,j,k,l,m,n,o;for([a,c].forEach(g),d=e(a),h=e(c),i=0;i<Math.max(d.length-1,h.length-1);i++){if(j=parseInt(d[i]||0,10),k=parseInt(h[i]||0,10),j>k)return 1;if(k>j)return-1}if(l=d[d.length-1],m=h[h.length-1],l&&m)for(n=l.split(".").map(f),o=m.split(".").map(f),i=0;i<Math.max(n.length,o.length);i++){if(n[i]===b||"string"==typeof o[i]&&"number"==typeof n[i])return-1;if(o[i]===b||"string"==typeof n[i]&&"number"==typeof o[i])return 1;if(n[i]>o[i])return 1;if(o[i]>n[i])return-1}else if(l||m)return l?-1:1;return 0}var c=/^v?(?:\d+)(\.(?:[x*]|\d+)(\.(?:[x*]|\d+)(\.(?:[x*]|\d+))?(?:-[\da-z\-]+(?:\.[\da-z\-]+)*)?(?:\+[\da-z\-]+(?:\.[\da-z\-]+)*)?)?)?$/i;a.compareVersions=h}(window,void 0);

/** https://github.com/dankogai/js-base64 BSD 3-Clause "New" or "Revised" License Copyright (c) 2014, Dan Kogai All rights reserved. */
(function(global,factory){typeof exports==="object"&&typeof module!=="undefined"?module.exports=factory(global):typeof define==="function"&&define.amd?define(factory):factory(global)})(typeof self!=="undefined"?self:typeof window!=="undefined"?window:typeof global!=="undefined"?global:this,function(global){"use strict";global=global||{};var _Base64=global.Base64;var version="2.5.1";var buffer;if(typeof module!=="undefined"&&module.exports){try{buffer=eval("require('buffer').Buffer")}catch(err){buffer=undefined}}var b64chars="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";var b64tab=function(bin){var t={};for(var i=0,l=bin.length;i<l;i++)t[bin.charAt(i)]=i;return t}(b64chars);var fromCharCode=String.fromCharCode;var cb_utob=function(c){if(c.length<2){var cc=c.charCodeAt(0);return cc<128?c:cc<2048?fromCharCode(192|cc>>>6)+fromCharCode(128|cc&63):fromCharCode(224|cc>>>12&15)+fromCharCode(128|cc>>>6&63)+fromCharCode(128|cc&63)}else{var cc=65536+(c.charCodeAt(0)-55296)*1024+(c.charCodeAt(1)-56320);return fromCharCode(240|cc>>>18&7)+fromCharCode(128|cc>>>12&63)+fromCharCode(128|cc>>>6&63)+fromCharCode(128|cc&63)}};var re_utob=/[\uD800-\uDBFF][\uDC00-\uDFFFF]|[^\x00-\x7F]/g;var utob=function(u){return u.replace(re_utob,cb_utob)};var cb_encode=function(ccc){var padlen=[0,2,1][ccc.length%3],ord=ccc.charCodeAt(0)<<16|(ccc.length>1?ccc.charCodeAt(1):0)<<8|(ccc.length>2?ccc.charCodeAt(2):0),chars=[b64chars.charAt(ord>>>18),b64chars.charAt(ord>>>12&63),padlen>=2?"=":b64chars.charAt(ord>>>6&63),padlen>=1?"=":b64chars.charAt(ord&63)];return chars.join("")};var btoa=global.btoa?function(b){return global.btoa(b)}:function(b){return b.replace(/[\s\S]{1,3}/g,cb_encode)};var _encode=buffer?buffer.from&&Uint8Array&&buffer.from!==Uint8Array.from?function(u){return(u.constructor===buffer.constructor?u:buffer.from(u)).toString("base64")}:function(u){return(u.constructor===buffer.constructor?u:new buffer(u)).toString("base64")}:function(u){return btoa(utob(u))};var encode=function(u,urisafe){return!urisafe?_encode(String(u)):_encode(String(u)).replace(/[+\/]/g,function(m0){return m0=="+"?"-":"_"}).replace(/=/g,"")};var encodeURI=function(u){return encode(u,true)};var re_btou=new RegExp(["[À-ß][-¿]","[à-ï][-¿]{2}","[ð-÷][-¿]{3}"].join("|"),"g");var cb_btou=function(cccc){switch(cccc.length){case 4:var cp=(7&cccc.charCodeAt(0))<<18|(63&cccc.charCodeAt(1))<<12|(63&cccc.charCodeAt(2))<<6|63&cccc.charCodeAt(3),offset=cp-65536;return fromCharCode((offset>>>10)+55296)+fromCharCode((offset&1023)+56320);case 3:return fromCharCode((15&cccc.charCodeAt(0))<<12|(63&cccc.charCodeAt(1))<<6|63&cccc.charCodeAt(2));default:return fromCharCode((31&cccc.charCodeAt(0))<<6|63&cccc.charCodeAt(1))}};var btou=function(b){return b.replace(re_btou,cb_btou)};var cb_decode=function(cccc){var len=cccc.length,padlen=len%4,n=(len>0?b64tab[cccc.charAt(0)]<<18:0)|(len>1?b64tab[cccc.charAt(1)]<<12:0)|(len>2?b64tab[cccc.charAt(2)]<<6:0)|(len>3?b64tab[cccc.charAt(3)]:0),chars=[fromCharCode(n>>>16),fromCharCode(n>>>8&255),fromCharCode(n&255)];chars.length-=[0,0,2,1][padlen];return chars.join("")};var _atob=global.atob?function(a){return global.atob(a)}:function(a){return a.replace(/\S{1,4}/g,cb_decode)};var atob=function(a){return _atob(String(a).replace(/[^A-Za-z0-9\+\/]/g,""))};var _decode=buffer?buffer.from&&Uint8Array&&buffer.from!==Uint8Array.from?function(a){return(a.constructor===buffer.constructor?a:buffer.from(a,"base64")).toString()}:function(a){return(a.constructor===buffer.constructor?a:new buffer(a,"base64")).toString()}:function(a){return btou(_atob(a))};var decode=function(a){return _decode(String(a).replace(/[-_]/g,function(m0){return m0=="-"?"+":"/"}).replace(/[^A-Za-z0-9\+\/]/g,""))};var noConflict=function(){var Base64=global.Base64;global.Base64=_Base64;return Base64};global.Base64={VERSION:version,atob:atob,btoa:btoa,fromBase64:decode,toBase64:encode,utob:utob,encode:encode,encodeURI:encodeURI,btou:btou,decode:decode,noConflict:noConflict,__buffer__:buffer};if(typeof Object.defineProperty==="function"){var noEnum=function(v){return{value:v,enumerable:false,writable:true,configurable:true}};global.Base64.extendString=function(){Object.defineProperty(String.prototype,"fromBase64",noEnum(function(){return decode(this)}));Object.defineProperty(String.prototype,"toBase64",noEnum(function(urisafe){return encode(this,urisafe)}));Object.defineProperty(String.prototype,"toBase64URI",noEnum(function(){return encode(this,true)}))}}if(global["Meteor"]){Base64=global.Base64}if(typeof module!=="undefined"&&module.exports){module.exports.Base64=global.Base64}else if(typeof define==="function"&&define.amd){define([],function(){return global.Base64})}return{Base64:global.Base64}});

$(function()
{
    var lastestVersion, isNewVersion, serverNotAvaliable = true;
    if(serverVersions && $.isArray(serverVersions))
    {
        serverNotAvaliable = false;
        if(serverVersions.length)
        {
            serverVersions.sort(function(v1, v2)
            {
               return window.compareVersions(v2.xxcVersion, v1.xxcVersion);
            });
            var lastestVersion = serverVersions[0];
            if(currentVersion && window.compareVersions(currentVersion.version, lastestVersion.xxcVersion) >= 0)
            {
                isNewVersion = true;
                lastestVersion = null;
            }
            else
            {
                var newVersions = [];

                for(var i = 0; i < serverVersions.length; ++i)
                {
                    var serverVersion =  serverVersions[i];
                    if(currentVersion && window.compareVersions(currentVersion.version, serverVersion.xxcVersion) >= 0)
                    {
                        break;
                    }
                    newVersions.push(serverVersion);
                }

                lastestVersion.newVersions = newVersions;
            }
        }
        else
        {
            isNewVersion = true;
        }
    }

    if(serverNotAvaliable) $('#errorBox').removeClass('hidden');
    else if(isNewVersion) $('#alreadyNewBox').removeClass('hidden');
    else
    {
        var $messageBox = $('#messageBox');
        $messageBox.removeClass('hidden');
        var $updateDetails = $('#updateDetails');
        $updateDetails.on('hide.zui.collapse', function()
        {
            $('#updateDetailsBtn>.icon').removeClass('icon-double-angle-up').addClass('icon-double-angle-down');
        }).on('show.zui.collapse', function()
        {
            $('#updateDetailsBtn>.icon').removeClass('icon-double-angle-down').addClass('icon-double-angle-up');
        });

        var $updateForm = $('#updateForm');
        $updateForm.on('show.zui.collapse', function()
        {
            $messageBox.find('.content>.actions').addClass('hidden');
            if($updateDetails.hasClass('in')) $updateDetails.collapse('hide');
        });

        $('.text-new-version').text(lastestVersion.xxcVersion);
        $('.text-version-summary').html(window.marked(lastestVersion.main));
        var $updateDetailsContent = $updateDetails.children('.article-content');
        var changeLogs = [];
        $.each(lastestVersion.newVersions, function(index, newVersion)
        {
            if(newVersion.xxcDesc)
            {
                if(lastestVersion.newVersions.length > 1)
                {
                    $updateDetailsContent.append('<h3 class="text-important"' + (!index ? ' style="margin-top: 0"' : '') + '>v' + newVersion.xxcVersion + '</h3>');
                    changeLogs.push('### v' + newVersion.xxcVersion);
                    if(newVersion.main)
                    {
                        $updateDetailsContent.append('<div style=" padding: 5px 10px; background: rgba(255,255,255,.3); margin-bottom: 10px; color: #666; border-radius: 4px;">' + window.marked(newVersion.main) + '</div>');
                        changeLogs.push(newVersion.main);
                    }
                }
                $updateDetailsContent.append('<div>' + window.marked(newVersion.xxcDesc) + '</div>');
                changeLogs.push(newVersion.xxcDesc);
            }
        });
        lastestVersion.changeLog = changeLogs.join('\n\n');

        var $downloadBtn = $('#downloadBtn');
        var $downloadTypes = $('#downloadTypes [type="checkbox"]');
        var $strategies = $('#strategies');
        $strategies.find('[type="radio"]:first').prop('checked', 'checked');

        $.each($downloadTypes, function()
        {
            var $this = $(this);
            var downloadType = $this.val();
            if(!lastestVersion.xxcDownload[downloadType]) $this.closest('.checkbox').addClass('hidden').prop('checked', false).attr('disabled', 'disabled');
        });

        $downloadTypes.on('click', function ()
        {
            var checkboxStatus = false;
            $downloadTypes.each(function()
            {
                if($(this).prop("checked") === true) checkboxStatus = true;
            });
            $('#downloadBtn').attr('disabled', !checkboxStatus);
        })
        
        if(!$('#downloadTypes .checkbox:not(.hidden)').length) $messageBox.find('.content>.actions').addClass('hidden');

        var downloadInfos = [];
        var serverClient = null;
        var updateDownloads = function()
        {
            var count = downloadInfos.length;
            if (!count) return;
            var successCount = 0;
            var errorCount = 0;
            $.each(downloadInfos, function(index, downloadInfo)
            {
                var $checkbox = $downloadTypes.filter('[data-type="' + downloadInfo.type + '"]');
                var $info = $checkbox.closest('.checkbox').children('.info').empty();
                if(downloadInfo.status === 'downloaded')
                {
                    successCount++;
                    $info.html('<i class="icon icon-check-circle"></i> ' + downloadSuccessText).attr('class', 'info text-success');
                    $checkbox.addClass('is-downloaded');
                }
                else if(downloadInfo.status === 'failed')
                {
                    errorCount++;
                    $info.html('<i class="icon icon-exclamation-sign"></i> ' + downloadFailText + '(' + downloadInfo.errorMessage + ')').attr('class', 'info text-error');
                    $info.siblings('.errorInfo').show();
                }
                else if(downloadInfo.status === 'downloading')
                {
                    $info.html('<i class="icon icon-spin icon-spinner-indicator"></i> ' + downloadingText).attr('class', 'info text-info');
                }
            });
            var finishCount = successCount + errorCount;

            $('#downloadErrorTip').toggleClass('hidden', finishCount !== count || errorCount === count || !errorCount);
            //$('#downloadFailedTip').toggleClass('hidden', finishCount !== count || errorCount !== count);

            if(errorCount === count)
            {
                $downloadBtn.text(downloadText).removeClass('disabled');
                $downloadTypes.attr('disabled', null);
            }
            else if(finishCount < count)
            {
                $downloadBtn.text(downloadingText + ' ' + Math.floor(100 * finishCount / count) + '%');
            }
            else
            {
                $downloadBtn.addClass('hidden');
                $('#updateBtns').removeClass('hidden');
            }

        };
        var downloadZip = function(downloadInfo)
        {
            var downloadUrl = downloadInfo.download;
            // var downloadMd5;
            if(typeof downloadUrl === 'object')
            {
                // downloadMd5 = downloadUrl.md5;
                downloadUrl = downloadUrl.url;
            }
            if (!downloadUrl)
            {
                downloadInfo.status = 'failed';
                updateDownloads();
                return;
            }
            downloadInfo.status = 'downloading';
            var url = createLink('client', 'download', 'version=' + lastestVersion.xxcVersion + '&link=' + Base64.encode(downloadUrl) + '&os=' + downloadInfo.type)
            $.ajax(
            {
                type:    'GET',
                url:      url,
                timeout:  0,
                dataType: 'json',
                success:  function(data)
                {
                    if(data && data.result === 'success')
                    {
                        downloadInfo.status = 'downloaded';
                        serverClient = $.extend({}, serverClient, data.client);
                    }
                    else
                    {
                        downloadInfo.status = 'failed';
                        if(data && data.message) downloadInfo.errorMessage = data.message;
                        else downloadInfo.errorMessage = 'Cannot download ' + downloadInfo.type + ' package from ' + downloadUrl;
                    }
                    updateDownloads();
                },
                error: function()
                {
                    downloadInfo.status = 'failed';
                    downloadInfo.errorMessage = downloadUrl;
                    updateDownloads();
                }
            });
            updateDownloads();
        };
        $downloadBtn.on('click', function()
        {
            serverClient = {};
            downloadInfos = [];
            $downloadTypes.attr('disabled', 'disabled').filter(':checked').not('.is-downloaded').each(function()
            {
                var $checkbox = $(this);
                var downloadType = $checkbox.val();
                downloadInfos.push(
                {
                    type: downloadType,
                    download: lastestVersion.xxcDownload[downloadType],
                    status: 'waiting'
                });
            });
            $('#downloadBtn').text(downloadingText).addClass('disabled');
            $.each(downloadInfos, function(index, downloadInfo)
            {
                setTimeout(function()
                {
                    downloadZip(downloadInfo);
                }, index * 1000)
            });
        });
        var serverDownloads;
        var saveClientUpdate = function(isRelease)
        {
            if(isRelease !== undefined) serverClient.status = isRelease ? 'released' : 'wait';
            serverClient.desc = lastestVersion.main;
            serverClient.changeLog = lastestVersion.changeLog;
            serverClient.strategy = $strategies.find('[type="radio"]:checked').val();
            var downloads = serverDownloads || serverClient.downloads;
            serverDownloads = downloads;
            if(typeof downloads === 'string') downloads = $.parseJSON(downloads);
            delete serverClient.createdBy;
            delete serverClient.createdDate;
            delete serverClient.editedBy;
            delete serverClient.editedDate;
            delete serverClient.downloads;
            $downloadTypes.filter(':checked').each(function()
            {
                var $checkbox = $(this);
                var downloadType = $checkbox.val();
                serverClient['downloads[' + downloadType + ']'] = downloads[downloadType];
            });

            $('#updateBtns').addClass('disabled');
            var $btn = $(isRelease ? '#publishUpdateBtn' : '#saveUpdateBtn');
            var btnText = $btn.text();
            $btn.text(submittingText);
            $.ajax(
            {
                type:    'POST',
                url:      createLink('client', 'edit', 'id=' + serverClient.id),
                dataType: 'json',
                data:     serverClient,
                success:  function(data)
                {
                    if(data && data.result === 'success')
                    {
                        $btn.popover('show');
                        setTimeout(function() {window.location.href = createLink('client', 'browse');}, 3000);
                    }
                    else
                    {
                        $.zui.messager.danger((data && data.message) || lang.timeout);
                        $('#updateBtns').removeClass('disabled');
                    }
                    $btn.text(btnText);
                },
                error: function()
                {
                    $.zui.messager.danger(lang.timeout);
                    $('#updateBtns').removeClass('disabled');
                    $btn.text(btnText);
                }
            });
        };
        $('#publishUpdateBtn').on('click', function() {saveClientUpdate(true);});
        $('#saveUpdateBtn').on('click', function() {saveClientUpdate(false);});
    }
});
