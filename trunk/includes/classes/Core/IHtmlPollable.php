<?
interface Core_IHtmlPollable{
	function PollHtml();
	function isDirtyHtml();
	function setDirtyHtml();
	function hasHtmlPollableChilds();
	function setHtmlPollableChilds();
	function isContentGenerator();
	function setContentGenerator();
}
?>