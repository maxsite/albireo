<style>
#upload_filedrag
{
	display: none;
	font-weight: bold;
	text-align: center;
	padding: 30px 0;
	margin: 0 0 20px 0;
	color: #555;
	border: 2px dashed #555;
	border-radius: 10px;
	cursor: default;
}

#upload_filedrag.hover
{
	color: #36D900;
	border-color: #36D900;
	border-style: solid;
}

#upload_progress p
{
	display: block;
	padding: 2px 5px;
	margin: 2px 0;
	border-radius: 5px;
	background: #eee;
	-background: #eee url("progress.png") 100% 0 repeat-y;
	font-size: .9rem;
}

#upload_progress p.success
{
	background: #9DE586;
}

#upload_progress p.failure
{
	background: #c00;
	color: #fff;
}
</style>