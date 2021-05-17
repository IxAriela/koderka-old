function ReplaceString(ABuf, AOldStr, ANewStr) {
	var tmp = "";
	if (ABuf == null) return tmp;
	var last_index = -1;
	var idx = ABuf.indexOf(AOldStr);
	while(idx >= 0) {
		var start = (last_index >= 0) ? last_index + AOldStr.length  : 0;
		tmp += ABuf.substring(start, idx) + ANewStr;
		last_index = idx;
		idx = ABuf.indexOf(AOldStr, idx + 1);
	}
	if (last_index >= 0) tmp += ABuf.substring(last_index + AOldStr.length);
	else tmp += ABuf;
	return tmp;
}