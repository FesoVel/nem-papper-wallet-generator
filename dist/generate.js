var generateWallet = function () {
	var data = [];
	var nem = require("nem-sdk").default;
	var rBytes = nem.crypto.nacl.randomBytes(32);
	data['privateKey'] = nem.utils.convert.ua2hex(rBytes);
	var keyPair = nem.crypto.keyPair.create(data['privateKey']);
	var publicKey = keyPair.publicKey.toString();
	data['address'] = nem.model.address.toAddress(keyPair.publicKey.toString(), nem.model.network.data.mainnet.id);

	var printWindow = window.open('', '', 'height=350,width=1090');
	printWindow.document.write('<!DOCTYPE html><html><head><title>NEM OFFLINE WALLET</title><script src="vendor/js/html2canvas.min.js"></script><script src="vendor/js/qrcode.min.js"></script><link href="https://fonts.googleapis.com/css?family=Ubuntu+Mono" rel="stylesheet"><link href="vendor/css/wallet.css" rel="stylesheet" type="text/css"/></head><body><script>function prepHref(linkElement) {var myDiv = document.getElementById("wallet-out");var myImage = myDiv.children[0];linkElement.href = myImage.src;}</script><div id="tooltip"><p>Click anywhere to download wallet.</p></div><div id="wallet" style="display: block; width: 1062px; height: 326px;"><div id="wrap"><div class="side"><h1>NEM WALLET</h1></div><div id="left"><div id="qr"></div><h2>YOUR ADDRESS</h2></div><div id="right"><div id="top"><h2>AMOUNT / NOTES</h2><div id="box"></div></div><div id="bottom"><h2>PRIVATE KEY:</h2><h2 id="privateKey">' + data["privateKey"] + '</h2></div></div><div class="side"><img src="vendor/img/nem_logo.png"><h5>NEM.IO</h5></div></div></div><a href="#" onclick="prepHref(this)" download="NEM-Papper-Wallet"><div id="wallet-out"></div></a><script>var qrcode=new QRCode("qr",{text: "' + data["address"] + '", width: 150, height: 150, colorDark : "#ffffff", colorLight : "#111111", correctLevel : QRCode.CorrectLevel.H});html2canvas(document.querySelector("#wallet"),{allowTaint : true,width:1062,}).then(canvas=>{canvas.id="canvas-wallet"; document.body.appendChild(canvas); var image=canvas.toDataURL("image/png"); var imageHtml=\'<img src="\';imageHtml +=image;imageHtml +=\'"/>\';document.getElementById(\"wallet-out\").innerHTML +=imageHtml;document.querySelector(\"#canvas-wallet\").remove();document.querySelector("#wallet").remove();});</script></body></html>');
	printWindow.document.close();
	var generateWallet = '';
	return 0;
}
jQuery(".generate").on("click", function () {
	var time = 4;
	var interval = setInterval(function () {
		if (time != 0) {
			jQuery("#counter").text(time)
			time--;
		} else {
			jQuery("#counter").addClass("hidden");
			jQuery("#btnPrint").removeClass("hidden");
			clearInterval(interval);
		}
	}, 1000);
});