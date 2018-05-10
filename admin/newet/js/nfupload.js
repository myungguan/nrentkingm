var nfUpload;                                           // 업로드 컴포넌트에서 사용되는 변수. (이름을 변경하면 안됩니다.)
var _NF_Uploader_Id = "NFUploadId";                      // 업로드 컴포넌트 객체명

/*************************
/* 용량 계산하는 함수
/************************/
function SizeCalc(fileSize) {
    var strSize = "";
    
    // 넘어오는 값 단위가 KB 단위이기 때문에 1024를 곱해준다.
    fileSize *= 1024;
    
    // GB 단위 계산
    if (fileSize > (1024*1024*1024))
	{
		fileSize /= (1024 * 1024 * 1024);
		
		strSize = Math.round(fileSize) + " GB";
	} else if (fileSize > (1024*1024))              // MB 단위 계산
	{
		fileSize /= (1024*1024);
		strSize = Math.round(fileSize) + " MB";
	}else if (fileSize > 1024)                      // KB 단위 계산
	{
		fileSize /= 1024;
		strSize = Math.round(fileSize) + " KB";
	} else
	{
		strSize = fileSize.toString() + " Byte";
	}
	
	return strSize;
}

var NFUpload = function(args) {
    try
    {
        if (navigator.appName.indexOf("explorer") > 0)
            document.execCommand("BackgroundImageCache", false, true);
    } catch(e) {
        Debug("Initialize error.");
    }
    
    try
    {
        this.Variables = {};
        this.Initialize(args);
        
        NFUpload.instances[this.GetValue("NFUploadId")] = this;
        
        this.Load();
    } catch(e) {
        Debug("Flash uploader error.");
    }
};

NFUpload.instances = {};

NFUpload.prototype.Initialize = function(args) {
    this.SetValue("NFUploadId", args.nf_upload_id, "");
    this.SetValue("Width", args.nf_width, "450");
    this.SetValue("Height", args.nf_height, "170");
    this.SetValue("ColumnHeader1", args.nf_field_name1, "File Name");
    this.SetValue("ColumnHeader2", args.nf_field_name2, "Size");
    this.SetValue("MaxFileSize", args.nf_max_file_size, "20480");
    this.SetValue("MaxFileCount", args.nf_max_file_count, "10");
    this.SetValue("UploadUrl", args.nf_upload_url, "");
    this.SetValue("FileFilter", args.nf_file_filter, "");
    this.SetValue("DataFieldName", args.nf_data_field_name, "DataFieldName");
    this.SetValue("FontFamily", args.nf_font_family, "New Times Roman");
    this.SetValue("FontSize", args.nf_font_size, "11");
    this.SetValue("FlashUrl", args.nf_flash_url, "");
    this.SetValue("FileOverwrite", args.nf_file_overwrite, true);
    this.SetValue("LimitExt", args.nf_limit_ext, "asp;php;aspx;jsp;html;htm");
	// [2008-10-28] Flash 10 support
    this.SetValue("ImgFileBrowse", args.nf_img_file_browse, "images/btn_file_browse.gif");
    this.SetValue("ImgFileBrowseWidth", args.nf_img_file_browse_width, "59");
    this.SetValue("ImgFileBrowseHeight", args.nf_img_file_delete_height, "22");
    this.SetValue("ImgFileDelete", args.nf_img_file_delete, "images/btn_file_delete.gif");
    this.SetValue("ImgFileDeleteWidth", args.nf_img_file_delete_width, "59");
    this.SetValue("ImgFileDeleteHeight", args.nf_img_file_delete_height, "22");
    this.SetValue("TotalSizeText", args.nf_total_size_text, "Total size: ");
    this.SetValue("TotalSizeFontFamily", args.nf_total_size_font_family, "굴림");
    this.SetValue("TotalSizeFotnSize", args.nf_total_size_font_size, "12");
};

NFUpload.prototype.SetValue = function(name, value, defValue) {
    
    if (typeof(value) == null)
        this.Variables[name] = defValue;
    else if (typeof(value) != null && value == null)
        this.Variables[name] = defValue;
    else
        this.Variables[name] = value;
    
    return this.Variables[name];
};

NFUpload.prototype.GetValue = function(name) {
    if (typeof(this.Variables[name]) == null || typeof(this.Variables[name]) == "undefined")
        return "";
    else
        return this.Variables[name];
};

NFUpload.prototype.GetFlashTag = function() {
    var flashTag = "";
    
    if (navigator.plugins && navigator.mimeTypes && navigator.plugins.length)
    {
        flashTag = "<embed type=\"application/x-shockwave-flash\" src=\"" + this.GetValue("FlashUrl") + "\" width=\"" + this.GetValue("Width") + "\" height=\"" + this.GetValue("Height") + "\"";
        flashTag += " Id=\"" + this.GetValue("NFUploadId") + "\" name=\"" + this.GetValue("NFUploadId") + "\" quality=\"high\" bgcolor=\"#869ca7\" type=\"application/x-shockwave-flash\"";
        flashTag += " allDomain=\"allDomain.com\"";
        flashTag += " flashvars=\"" +  this.GetFlashVars() + "\" />";
    } else
    {
        flashTag = "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\"" + this.GetValue("Width") + "\" height=\"" + this.GetValue("Height") + "\"";
        flashTag += " Id=\"" + this.GetValue("NFUploadId") + "\" codebase=\"http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab\">";
        flashTag += "<param name=\"movie\" value=\"" + this.GetValue("FlashUrl") + "\" />";
        flashTag += "<param name=\"bgcolor\" value=\"#869ca7\" />";
        flashTag += "<param name=\"quality\" value=\"high\" />";
        flashTag += "<param name=\"flashvars\" value=\"" + this.GetFlashVars() + "\" />";
        flashTag += "</object>";
    }
    
    return flashTag;
};

NFUpload.prototype.GetFlashVars = function() {
    var vars = "";
    
    vars = "NFUploadId=" + encodeURIComponent(this.GetValue("NFUploadId"));
    vars += "&Width=" + encodeURIComponent(this.GetValue("Width"));
    vars += "&Height=" + encodeURIComponent(this.GetValue("Height"));
    vars += "&ColumnHeader1=" + encodeURIComponent(this.GetValue("ColumnHeader1"));
    vars += "&ColumnHeader2=" + encodeURIComponent(this.GetValue("ColumnHeader2"));
    vars += "&MaxFileSize=" + encodeURIComponent(this.GetValue("MaxFileSize"));
    vars += "&MaxFileCount=" + encodeURIComponent(this.GetValue("MaxFileCount"));
    vars += "&UploadUrl=" + encodeURIComponent(this.GetValue("UploadUrl"));
    vars += "&FileFilter=" + encodeURIComponent(this.GetValue("FileFilter"));
    vars += "&DataFieldName=" + encodeURIComponent(this.GetValue("DataFieldName"));
    vars += "&FontFamily=" + encodeURIComponent(this.GetValue("FontFamily"));
    vars += "&FlashUrl=" + encodeURIComponent(this.GetValue("FlashUrl"));
    vars += "&FileOverwrite=" + encodeURIComponent(this.GetValue("FileOverwrite"));
    vars += "&LimitExt=" + encodeURIComponent(this.GetValue("LimitExt"));
	// [2008-10-28] Flash 10 support
    vars += "&ImgFileBrowse=" + encodeURIComponent(this.GetValue("ImgFileBrowse"));
    vars += "&ImgFileBrowseWidth=" + encodeURIComponent(this.GetValue("ImgFileBrowseWidth"));
    vars += "&ImgFileBrowseHeight=" + encodeURIComponent(this.GetValue("ImgFileBrowseHeight"));
    vars += "&ImgFileDelete=" + encodeURIComponent(this.GetValue("ImgFileDelete"));
    vars += "&ImgFileDeleteWidth=" + encodeURIComponent(this.GetValue("ImgFileDeleteWidth"));
    vars += "&ImgFileDeleteHeight=" + encodeURIComponent(this.GetValue("ImgFileDeleteHeight"));
    vars += "&TotalSizeText=" + encodeURIComponent(this.GetValue("TotalSizeText"));
    vars += "&TotalSizeFontFamily=" + encodeURIComponent(this.GetValue("TotalSizeFontFamily"));
    vars += "&TotalSizeFontSize=" + encodeURIComponent(this.GetValue("TotalSizeFontSize"));
    
    return vars;
};

NFUpload.prototype.Load = function() {
    document.write(this.GetFlashTag());
};

/*****************************************************
/* 파일 선택 함수
/****************************************************/
NFUpload.prototype.FileBrowse = function() {
    document.all[this.GetValue("NFUploadId")].FileBrowse();
};

/*****************************************************
/* 업로드 함수
/****************************************************/
NFUpload.prototype.FileUpload = function() {
    document.all[this.GetValue("NFUploadId")].FileUpload();
};

/*****************************************************
/* 파일 삭제 함수
/****************************************************/
NFUpload.prototype.FIleDelete = function() {
    document.all[this.GetValue("NFUploadId")].FileDelete();
};

/*****************************************************
/* 모든 파일 삭제 함수
/****************************************************/
NFUpload.prototype.AllFileDelete = function() {
    document.all[this.GetValue("NFUploadId")].AllFileDelete();
}

function Debug(value) {
    alert(value);
    return;
}