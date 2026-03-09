<?xml version="1.0" encoding="UTF-8"?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
    <ShortName>Ziara Travel</ShortName>
    <Description>Search flights and hotels with Ziara Travel</Description>
    <Tags>flights hotels travel booking kenya</Tags>
    <Contact>info@ziaratravel.com</Contact>
    <Url type="text/html" method="get" template="{{ url('/search') }}?q={searchTerms}"/>
    <Image height="64" width="64" type="image/png">{{ url('/images/icons/icon-64x64.png') }}</Image>
    <Image height="16" width="16" type="image/vnd.microsoft.icon">{{ url('/favicon.ico') }}</Image>
    <Developer>Ziara Travel</Developer>
    <Attribution>Ziara Travel flight and hotel search</Attribution>
    <Language>en-us</Language>
    <InputEncoding>UTF-8</InputEncoding>
    <OutputEncoding>UTF-8</OutputEncoding>
</OpenSearchDescription>