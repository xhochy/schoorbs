<?xml version="1.0" encoding="utf-8" ?>
<rsp stat="ok">
	{foreach from=$entries item=entry}
		<entry id="{$entry.id}" start_time="{$entry.start_time}" end_time="{$entry.end_time}" name="{$entry.name}" description="{$entry.description}" create_by="{$entry.create_by}" />
	{/foreach}
</rsp>