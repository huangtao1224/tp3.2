<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2017071207726027",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIICXQIBAAKBgQDAS19DVuxe6sryIf7RLjWo5vNJjuFXxJqsElaJIHhvwpKanJZMBMzX15qDPpLd+KvSq3XO4S6nnsGqUOnqpBKt+4+98OjDu/MTJqej0qe2oSRkWF60THCPPItX1o+2QUG/gMBGPAt/GIFjklE+u5FtKHNpvXxo2BEwti6SiJ8ywwIDAQABAoGAP1kVOwPpvqMu1HGqlpLYjpn2z+bICbf1FHa+F1KhGoBI97JaORTjvr+CYXY9v+5p/G8L0mmQixvbxRX+2ZPPqvrimYkf2OvVttHxLu4yZGszfbuwN9ufLqcmshDdL7qLHfiQJjnhE53YOOeZ5fxu0LDp5jP3m2RtzpsmjIDuN6ECQQDrSED/BWeyaPj7vjGVDLHGA2Ka2UoGjgciv7zIYNa1Oa119xjNIgiZCHsgaiDZEdRDYoFpR7TIOF0e/U+cEUA7AkEA0ToVk2qIXSBNoe+T+GPyOJbtyEO1BghacjAL8Uik4Uyrw7YmIPhWhQYAYAk/P1T3+FzgSo5mX0bl1ux+MXX3GQJBAJ1o+8MilJvySxHxzy5PDejSfvfmg/Yas392FBFjaIJakkioBnnHWUU5PVcUaeGQYhirILWU+cgynWqMQuTBTd0CQQDKd+cBLQqg8+2sU2dh63YvBP892SeImvTLo2srJx1HWSau0cm8BhXTzKb4SHqIEvWTxmzWOU8fzEAG5/yGaJ2hAkAJ222KzZA9e4uT/YA73vjh8saYo9i8ukLAgNGlYnFCbauW9h3pwLJaKJcrNQc1UjHwNodMChBw+nlsyVyYhtEd",
		
		//异步通知地址
		'notify_url' => "http://bn.hbjylm.net/index.php/alipay/mobile_return",
		
		//同步跳转
		'return_url' => "http://bn.hbjylm.net/index.php/alipay/link_return",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCIdpArnmtj0235qrya2ssmvJSxTz/W6Y6pefuQIgUpmyhjZLWJf99lr52YqWFw22AxNsWcPEh67Ar1pp8zvyS5Y8CPBQiAMKCJsFDDBGbeQbvOyE/uRIkrqzpd5HbE+7ushn+UFerSEzUVwzHWWbeLAnVQPC5JUL3ZWUDyTAisLwIDAQAB",
		
	
);


