var labels = ['Fuel for Moby', 'Buy us a drink!', 'Bitcoin Donations Accepted', 'Can you spare a bitcoin?',
	'Thirsty. Please help.', 'Homeless. Please help.'
]
var rnd = getRandomArbitrary(0, labels.length - 1);
CoinWidgetCom.go({
	wallet_address: '1MAZhKpWvDxGCUAth5zJNad4YfNoZ8PxzP',
	currency: 'bitcoin',
	counter: 'hide',
	lbl_button: labels[rnd],
	lbl_count: 'donations',
	lbl_amount: 'BTC',
	lbl_address: 'Use address below to send bitcoin. Thanks!',
	qrcode: true,
	alignment: 'bl',
	decimals: 8,
	size: "big",
	color: "light",
	countdownFrom: "0",
	element: "#coinwidget-bitcoin-1MAZhKpWvDxGCUAth5zJNad4YfNoZ8PxzP",
	onShow: function() {},
	onHide: function() {}
});


function getRandomArbitrary(min, max) {
	return Math.round(Math.random() * (max - min) + min);
}
