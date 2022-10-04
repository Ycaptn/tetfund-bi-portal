/*Dashboard2 Init*/
"use strict"; 

/*****Ready function start*****/
$(document).ready(function(){
	if( $('#pie_chart_4').length > 0 ){
		$('#pie_chart_4').easyPieChart({
			barColor : '#4e44e7',
			lineWidth: 20,
			animate: 3000,
			size:	165,
			lineCap: 'square',
			trackColor: '#f4f4f4',
			scaleColor: false,
			onStep: function(from, to, percent) {
				$(this.el).find('.percent').text(Math.round(percent));
			}
		});
	}
	
	if( $('#datable_1').length > 0 )
		$('#datable_1').DataTable({
			"bFilter": false,
			"bLengthChange": false,
			"bPaginate": false,
			"bInfo": false,
			
		});
});
/*****Ready function end*****/

/*****Load function start*****/
$(window).on("load",function(){
	window.setTimeout(function(){
		$.toast({
			heading: 'Welcome to Daffy',
			text: 'Use the predefined ones, or specify a custom position object.',
			position: 'top-left',
			loaderBg:'#f8b32d',
			icon: '',
			hideAfter: 3500, 
			stack: 6
		});
	}, 3000);
});
/*****Load function* end*****/

/*****E-Charts function start*****/
var echartsConfig = function() { 
	if( $('#e_chart_1').length > 0 ){
		var eChart_1 = echarts.init(document.getElementById('e_chart_1'));
		var option = {
		   visualMap: {
				min: 15202,
				max: 159980,
				dimension: 1,
				left: 'right',
				top: 'top',
				text: ['HIGH', 'LOW'], // 文本，默认为数值文本
				calculable: true,
				textStyle: {
						color: '#878787',
					},
				inRange: {
					color: ['#4e44e7', '#e11d8e']
				}
			},
			grid: {
				left: '3%',
				right: '4%',
				bottom: '3%',
				containLabel: true
			},
			tooltip: {
				trigger: 'item',
				showDelay: 0,
				backgroundColor: 'rgba(33,33,33,1)',
				borderRadius:0,
				padding:10,
				axisPointer: {
					type: 'cross',
					show: true,
					lineStyle: {
						type: 'dashed',
						width: 1
					},
					label: {
						backgroundColor: 'rgba(33,33,33,1)'
					}
				},
				textStyle: {
					color: '#fff',
					fontStyle: 'normal',
					fontWeight: 'normal',
					fontFamily: "'Montserrat', sans-serif",
					fontSize: 12
				},
				formatter: function(params) {
					if (params.value.length > 1) {
						return params.seriesName + ' :<br/>' +
							params.value[0] + '㎡ ' +
							params.value[1] + ' CNY/㎡ ';
					} else {
						return params.seriesName + ' :<br/>' +
							params.name + ' : ' +
							params.value + ' CNY/㎡ ';
					}
				}
			},

			xAxis: [{
				type: 'value',
				scale: true,
				nameTextStyle: {
					color: '#878787',
					fontSize: 12,
				},
				axisLine: {
					show:false
				},
				axisLabel: {
					formatter: '{value}㎡',
					textStyle: {
						color: '#878787'
					}
				},
				splitLine: {
					show:false,
				}
			}],
			yAxis: [{
				type: 'value',
				scale: true,
				axisLine: {
					show:false
				},
				axisLabel: {
					formatter: '{value} CNY/㎡',
					textStyle: {
						color: '#878787'
					}
				},
				nameTextStyle: {
					color: '#878787',
					fontSize: 12
				},
				splitLine: {
					show:false
				}
			}],
			series: [{
					name: 'Price area',
					type: 'scatter',
					data: [
						[399.48, 75098],
						[398.61, 107624],
						[390.44, 56347],
						[368.52, 25779],
						[367.31, 40293],
						[367.19, 62638],
						[357.76, 74911],
						[353.44, 45836],
						[349.71, 65769],
						[336.9, 129000],
						[335.85, 77416],
						[335.67, 73883],
						[330.86, 119386],
						[327.27, 44306],
						[326.15, 49058],
						[325.1, 38143],
						[324.98, 52311],
						[324.22, 98699],
						[321.42, 28623],
						[321.42, 28623],
						[321.06, 96556],
						[321.05, 119920],
						[320.1, 34990],
						[320.1, 96845],
						[319.89, 89406],
						[317.94, 100648],
						[317.61, 99179],
						[316.68, 52104],
						[314.28, 128866],
						[314.28, 108184],
						[314.01, 36624],
						[311.95, 58664],
						[311.95, 58664],
						[310.46, 18038],
						[310.35, 69599],
						[310.17, 122514],
						[307.68, 107255],
						[306.87, 25418],
						[306.82, 97778],
						[306.66, 42393],
						[306.4, 101175],
						[305.54, 130916],
						[305.37, 74991],
						[304.91, 55755],
						[304.08, 118390],
						[303.86, 87541],
						[303.61, 118574],
						[303.43, 95574],
						[303.06, 102290],
						[302.62, 88164],
						[301.94, 42393],
						[301.94, 42393],
						[301.3, 56423],
						[298.79, 86951],
						[298.02, 45299],
						[298.02, 45299],
						[297.51, 73275],
						[297.18, 65954],
						[296.18, 88798],
						[295.62, 118396],
						[295.56, 128570],
						[295.27, 59268],
						[294.77, 26801],
						[294.62, 67545],
						[293.77, 81697],
						[293.35, 22158],
						[292.56, 45119],
						[292.21, 31827],
						[292.03, 89032],
						[291.27, 34333],
						[291.24, 68672],
						[290.69, 106643],
						[289.82, 117315],
						[289.65, 139997],
						[289.45, 55278],
						[289.45, 55278],
						[289.01, 24913],
						[289.01, 24913],
						[288.75, 66840],
						[288.75, 66840],
						[287.7, 116441],
						[286.87, 97606],
						[286.43, 48878],
						[286.26, 83840],
						[286.26, 59387],
						[286.01, 132863],
						[284.99, 94741],
						[284.62, 119458],
						[284.43, 91411],
						[283.6, 129937],
						[283.1, 65348],
						[282.92, 134314],
						[282.02, 49642],
						[281.02, 69391],
						[280.96, 34881],
						[280.03, 49995],
						[278.79, 99717],
						[278.52, 21543],
						[278.2, 113228],
						[278.06, 61138],
						[278.01, 114996],
						[277.62, 93221],
						[276.73, 104796],
						[275.99, 137687],
						[275.71, 111712],
						[275.62, 34468],
						[275.08, 39989],
						[274.51, 49179],
						[273.57, 100523],
						[273.19, 36166],
						[272.85, 39216],
						[271.32, 64500],
						[271.18, 95878],
						[270.93, 75666],
						[269.88, 68549],
						[269.27, 61277],
						[267.44, 74784],
						[267.21, 95057],
						[266.93, 65561],
						[266.66, 97503],
						[266.66, 97503],
						[266.63, 33755],
						[266.59, 37511],
						[266.3, 103267],
						[266.01, 105260],
						[265.55, 55734],
						[263.43, 37961],
						[263.42, 33407],
						[263.27, 68371],
						[263.04, 49423],
						[262.83, 49462],
						[262.37, 38038],
						[262.1, 72492],
						[261.85, 95475],
						[261.35, 89918],
						[261.19, 118688],
						[260.91, 70906],
						[260.61, 35609],
						[260.34, 72982],
						[259.97, 84626],
						[259.74, 100101],
						[259.57, 36599],
						[259.25, 96433],
						[258.5, 34817],
						[257.83, 62057],
						[257.55, 71831],
						[257.4, 85471],
						[257.35, 95202],
						[256.87, 101219],
						[256.04, 32417],
						[255.52, 129149],
						[254.97, 61498],
						[253.4, 50000],
						[252.61, 53838],
						[251.29, 37805],
						[251.29, 38601],
						[251.29, 37805],
						[251.29, 38601],
						[251.21, 33041],
						[251.01, 24701],
						[250.97, 87660],
						[249.64, 32047],
						[249.33, 80135],
						[249.27, 78630],
						[248.93, 49613],
						[248.93, 49613],
						[248.68, 44234],
						[248.68, 44234],
						[248.08, 141084],
						[247.9, 133119],
						[247.83, 136384],
						[247.22, 44010],
						[247.22, 44010],
						[247.11, 82960],
						[246.76, 129681],
						[246.65, 60815],
						[246.38, 93352],
						[246.32, 109573],
						[245.38, 88842],
						[245.3, 89687],
						[245.2, 87684],
						[245.19, 28550],
						[244.96, 35108],
						[244.19, 53156],
						[244.19, 53156],
						[243.97, 57262],
						[243.67, 23393],
						[243.33, 34932],
						[243.33, 34932],
						[242.92, 59773],
						[242.89, 63815],
						[242.89, 121455],
						[242.68, 39147],
						[242.68, 39147],
						[241.67, 44690],
						[241.28, 95325],
						[241.1, 27085],
						[240.78, 91370],
						[240.78, 82233],
						[239.96, 40007],
						[239.31, 64770],
						[238.51, 117396],
						[238.07, 107112],
						[238.05, 71414],
						[238.03, 74781],
						[237.93, 50436],
						[237.93, 50436],
						[237.83, 25649],
						[237.58, 75764],
						[237.44, 24638],
						[237.2, 70827],
						[237.2, 71670],
						[236.99, 66670],
						[236.94, 23635],
						[236.68, 101403],
						[235.86, 27559],
						[235.4, 29737],
						[235.23, 47996],
						[235.23, 51014],
						[235, 148937],
						[234.89, 108562],
						[234.89, 113245],
						[234.59, 80993],
						[234.55, 28992],
						[234.45, 106633],
						[234.2, 38002],
						[234.12, 136683],
						[233.88, 45152],
						[233.64, 36381],
						[233.2, 75472],
						[233.06, 90020],
						[233.06, 89977],
						[232.67, 58023],
						[232.6, 122528],
						[232.52, 27095],
						[232.47, 129049],
						[232.47, 75279],
						[232.36, 124807],
						[232.28, 37886],
						[232.19, 139972],
						[231.85, 74963],
						[231.64, 26982],
						[231.39, 32413],
						[231.24, 80004],
						[231.17, 51045],
						[230.86, 56312],
						[230.75, 130011],
						[230.64, 86716],
						[230.17, 59956],
						[229.98, 34786],
						[229.97, 82620],
						[229.93, 82634],
						[229.69, 59994],
						[229.5, 48802],
						[229.5, 48802],
						[229.07, 34750],
						[228.93, 98284],
						[228.71, 56404],
						[228.34, 95472],
						[228.31, 82345],
						[228.28, 60453],
						[227.94, 85023],
						[227.72, 68067],
						[227.66, 92243],
						[227.45, 118708],
						[227.34, 103370],
						[227.3, 46635],
						[227.3, 74792],
						[227.14, 52831],
						[226.98, 44057],
						[226.84, 141069],
						[226.78, 110000],
						[226.47, 92993],
						[225.76, 88590],
						[225.74, 86383],
						[224.95, 80018],
						[224.62, 97944],
						[224.02, 74101],
						[223.99, 84826],
						[223.94, 53586],
						[223.94, 53586],
						[223.9, 60295],
						[223.79, 98307],
						[223.52, 102900],
						[222.79, 27829],
						[222.79, 27829],
						[222.65, 77701],
						[222.57, 22016],
						[222.08, 69795],
						[222, 60811],
						[221.9, 80217],
						[221.8, 47340],
						[221.65, 27025],
						[221.56, 126377],
						[221.54, 54167],
						[221.14, 64937],
						[220.76, 46431],
						[220.72, 58853],
						[220.63, 28102],
						[220.43, 38788],
						[220.32, 45389],
						[220.32, 45389],
						[220.1, 70423],
						[219.94, 31736],
						[219.45, 60151],
						[219.26, 136779],
						[219.19, 24956],
						[218.95, 75360],
						[218.81, 121110],
						[218.81, 143961],
						[218.79, 108781],
						[218.78, 105129],
						[218.77, 29255],
						[218.6, 72279],
						[218.46, 114438],
						[218.36, 66405],
						[218.13, 68583],
						[218.13, 27507],
						[217.54, 41372],
						[217.26, 87453],
						[217.24, 89763],
						[217.24, 89763],
						[216.84, 96846],
						[216.65, 34619],
						[216.24, 110988],
						[216.16, 36085],
						[216.15, 69397],
						[216.08, 37024],
						[216.03, 31478],
						[215.34, 71980],
						[214.82, 58189],
						[214.67, 53571],
						[214.51, 22843],
						[214.43, 69953],
						[214.11, 41988],
						[214.11, 41988],
						[214.02, 59808],
						[214.02, 51398],
						[213.91, 28985],
						[213.63, 82386],
						[213.44, 100731],
						[213.41, 103088],
						[213.38, 24956],
						[211.19, 35514],
						[211.12, 80523],
						[211.1, 104217],
						[210.97, 31190],
						[210.63, 31810],
						[210.42, 42772],
						[210.23, 117491],
						[210.19, 64228],
						[210.19, 57092],
						[210.01, 133327],
						[209.97, 32862],
						[209.73, 57217],
						[208.55, 89667],
						[208.29, 36728],
						[208.29, 36728],
						[208.1, 108602],
						[208, 43270],
						[207.93, 28856],
						[207.65, 51530],
						[207.49, 115669],
						[207.48, 55428],
						[207.38, 119588],
						[207.22, 103272],
						[207.08, 74851],
						[207.02, 86949],
						[206.72, 33863],
						[204.57, 41551],
						[204.55, 44977],
						[204.47, 68470],
						[202.24, 27690],
						[201.89, 41112],
						[201.56, 32745],
						[201.49, 65512],
						[201.44, 83400],
						[201.36, 119190],
						[201.19, 84498],
						[201.17, 59950],
						[200.9, 36999],
						[200.81, 117027],
						[200.66, 104655],
						[200.58, 99711],
						[200.56, 67312],
						[200.48, 87790],
						[200.46, 111245],
						[200.39, 49903],
						[200.35, 117295],
						[200.31, 99846],
						[200.24, 37456],
						[200.19, 114891],
						[200.19, 99906],
						[200.06, 119965],
						[200, 87500],
						[199.8, 45046],
						[198.94, 110587],
						[198.85, 50290],
						[198.85, 41741],
						[198.83, 34201],
						[198.74, 33059],
						[198.71, 63913],
						[198.68, 28690],
						[198.63, 133414],
						[198.61, 118323],
						[198.52, 89664],
						[198.47, 44844],
						[198.13, 80756],
						[198.09, 159978],
						[197.86, 89963],
						[197.69, 65760],
						[197.69, 55643],
						[197.37, 32427],
						[197.37, 32427],
						[197.36, 58270],
						[197.32, 116562],
						[197.32, 55748],
						[197.16, 55793],
						[197.16, 48692],
						[197.15, 40072],
						[197.11, 48197],
						[197.09, 101477],
						[196.95, 35035],
						[196.83, 59697],
						[196.7, 61007],
						[196.05, 29534],
						[195.99, 89954],
						[195.98, 109706],
						[195.85, 31045],
						[195.85, 29615],
						[195.83, 97023],
						[195.8, 44434],
						[195.7, 97088],
						[195.49, 46039],
						[195.48, 79037],
						[195.26, 130083],
						[195, 46154],
						[194.77, 81122],
						[194.76, 35942],
						[194.76, 35942],
						[194.48, 105358],
						[194.35, 41421],
						[194.25, 39125],
						[194.14, 102195],
						[194.01, 60822],
						[194.01, 54121],
						[193.98, 149500],
						[193.92, 128920],
						[193.91, 50539],
						[193.87, 137103],
						[193.76, 74835],
						[193.71, 31491],
						[193.6, 47005],
						[193.52, 50641],
						[193.47, 69779],
						[193.36, 134465],
						[193.36, 108606],
						[193.21, 129963],
						[193.2, 71429],
						[193.09, 49718],
						[193.07, 79971],
						[193.07, 74999],
						[193.04, 26938],
						[192.85, 33705],
						[192.82, 28525],
						[192.66, 36334],
						[192.62, 26997],
						[192.57, 72597],
						[192.52, 80512],
						[192.45, 25877],
						[192.32, 61877],
						[192.32, 61877],
						[192.29, 89969],
						[192.27, 81657],
						[192.19, 104012],
						[192.18, 41628],
						[192.16, 94973],
						[192.1, 65591],
						[192.1, 65591],
						[191.81, 144988],
						[191.69, 148000],
						[191.65, 93922],
						[191.51, 40729],
						[191.51, 57439],
						[191.41, 37355],
						[191.37, 45462],
						[191.33, 30837],
						[191.2, 78452],
						[191.2, 34519],
						[191.2, 34519],
						[191.17, 27201],
						[191.17, 30863],
						[191.17, 28771],
						[191.09, 83731],
						[191.08, 112519],
						[191.01, 37747],
						[191.01, 37747],
						[190.97, 109965],
						[190.95, 27495],
						[190.94, 149786],
						[190.9, 104767],
						[190.77, 15202],
						[190.68, 41956],
						[190.67, 85489],
						[190.65, 91792],
						[190.62, 60330],
						[190.62, 60330],
						[190.6, 104932],
						[190.46, 46204],
						[190.43, 103976],
						[190.32, 79866],
						[190.32, 78290],
						[190.19, 34177],
						[190.19, 86756],
						[189.96, 22637],
						[189.9, 139969],
						[189.85, 74796],
						[189.85, 35292],
						[189.82, 25182],
						[189.64, 110737],
						[189.34, 44893],
						[189.06, 58183],
						[188.99, 44976],
						[188.99, 92598],
						[188.96, 63506],
						[188.87, 45005],
						[188.87, 75714],
						[188.87, 66184],
						[188.69, 29149],
						[188.64, 76866],
						[188.53, 50921],
						[188.5, 100796],
						[188.26, 116860],
						[188.26, 33465],
						[188.07, 49982],
						[188, 41490],
						[187.98, 95223],
						[187.95, 54962],
						[187.95, 54962],
						[187.94, 73960],
						[187.93, 41505],
						[187.86, 133078],
						[187.86, 55893],
						[187.84, 46317],
						[187.71, 93229],
						[187.69, 93239],
						[187.69, 93239],
						[187.65, 95924],
						[187.65, 95924],
						[187.65, 95924],
						[187.65, 95924],
						[187.65, 95924],
						[187.65, 95924],
						[187.65, 95924],
						[187.65, 95924],
						[187.65, 95924],
						[187.32, 48047],
						[187.32, 48047],
						[187.32, 48047],
						[187.32, 48047],
						[187.32, 48047],
						[187.32, 48047],
						[187.32, 48047],
						[187.32, 48047],
						[187.32, 48047],
						[187.32, 48047],
						[187.32, 48047],
						[187.27, 35778],
						[187.23, 58752],
						[187.18, 42740],
						[187.16, 69193],
						[187.16, 35532],
						[187.16, 106861],
						[187.12, 58786],
						[187.06, 64953],
						[186.99, 93588],
						[186.93, 88269],
						[186.9, 112360],
						[186.88, 88025],
						[186.87, 42757],
						[186.72, 30045],
						[186.59, 40196],
						[186.57, 25782],
						[186.54, 53608],
						[186.5, 42896],
						[186.49, 40217],
						[186.46, 64357],
						[186.44, 28428],
						[186.42, 43987],
						[186.16, 59089],
						[186.04, 40314],
						[185.96, 80125],
						[185.6, 94182],
						[185.6, 94289],
						[185.55, 112100],
						[185.53, 25279],
						[185.18, 62642],
						[185.14, 104948],
						[185.12, 84918],
						[185.11, 40517],
						[185.05, 83762],
						[184.88, 58417],
						[184.8, 105520],
						[184.64, 37100],
						[184.53, 80746],
						[184.44, 56930],
						[184.37, 47188],
						[184.37, 47188],
						[184.3, 73251],
						[184.07, 62477],
						[183.91, 69600],
						[183.81, 80518],
						[183.66, 73506],
						[183.62, 81691],
						[183.55, 54482],
						[183.49, 29975],
						[183.44, 30528],
						[183.18, 27296],
						[183.13, 92831],
						[183.08, 57352],
					],
					symbolSize:30,
					markLine: {
						data: [{
							type: 'average',
							name: 'price'
						}],
						lineStyle:{
							normal:{
							  color:'#4e44e7',  
							}
						}
					}
				},


			]
		};
		eChart_1.setOption(option);
		eChart_1.resize();
	}
	if( $('#e_chart_2').length > 0 ){
		var eChart_2 = echarts.init(document.getElementById('e_chart_2'));
		var option1 = {
			angleAxis: {
				max: 100,
				axisLabel: {
					textStyle: {
						color: '#878787'
					}
				},
				axisLine: {
					lineStyle: {
						color: 'rgba(33, 33, 33, 0.1)'
					}
				}
			},
			color: ['#4e44e7', '#e11d8e', '#958FEF'],
			polar:{
				radius:'70%',
				axisLabel: {
					textStyle: {
						color: '#878787'
					}
				}
			},
			radiusAxis: {
				type: 'category',
				data: ['Dt1', 'Dt2', 'Dt3'],
				z: 10,
				show:false,
				axisLine: {
					lineStyle: {
						color: 'rgba(33, 33, 33, 0.1)'
					}
				}
			},
			
			series: [{
				type: 'bar',
				data: [70, 0, 0],
				coordinateSystem: 'polar',
				name: 'Dt1',
				radius: [0, '30%'],
				stack: 'a'
			}, {
				type: 'bar',
				data: [0, 40, 0],
				coordinateSystem: 'polar',
				name: 'Dt2',
				stack: 'a'
			},{
				type: 'bar',
				data: [0, 0, 80],
				coordinateSystem: 'polar',
				name: 'Dt3',
				stack: 'a'
			}]
		};

		eChart_2.setOption(option1);
		eChart_2.resize();
	}
	if( $('#e_chart_3').length > 0 ){
		var eChart_3 = echarts.init(document.getElementById('e_chart_3'));
		var data = [{
			value: 5713,
			name: ''
		}, {
			value: 9938,
			name: ''
		}, {
			value: 17623,
			name: ''
		}];
		var option3 = {
			tooltip: {
				show: true,
				trigger: 'item',
				backgroundColor: 'rgba(33,33,33,1)',
				borderRadius:0,
				padding:10,
				formatter: "{b}: {c} ({d}%)",
				textStyle: {
					color: '#fff',
					fontStyle: 'normal',
					fontWeight: 'normal',
					fontFamily: "'Montserrat', sans-serif",
					fontSize: 12
				}	
			},
			series: [{
				type: 'pie',
				selectedMode: 'single',
				radius: ['80%', '30%'],
				color: ['#4e44e7', '#e11d8e', '#958FEF'],
				labelLine: {
					normal: {
						show: false
					}
				},
				data: data
			}]
		};
		eChart_3.setOption(option3);
		eChart_3.resize();
	}
}
/*****E-Charts function end*****/

/*****Sparkline function start*****/
var sparklineLogin = function() { 
		if( $('#sparkline_4').length > 0 ){
			$("#sparkline_4").sparkline([2,4,4,6,8,5,6,4,8,6,6,2 ], {
				type: 'line',
				width: '100%',
				height: '45',
				lineColor: '#4e44e7',
				fillColor: '#4e44e7',
				minSpotColor: '#4e44e7',
				maxSpotColor: '#4e44e7',
				spotColor: '#4e44e7',
				highlightLineColor: '#4e44e7',
				highlightSpotColor: '#4e44e7'
			});
		}	
		if( $('#sparkline_5').length > 0 ){
			$("#sparkline_5").sparkline([0,2,8,6,8], {
				type: 'bar',
				width: '100%',
				height: '45',
				barWidth: '10',
				resize: true,
				barSpacing: '10',
				barColor: '#4e44e7',
				highlightSpotColor: '#4e44e7'
			});
		}	
		if( $('#sparkline_6').length > 0 ){
			$("#sparkline_6").sparkline([0, 23, 43, 35, 44, 45, 56, 37, 40, 45, 56, 7, 10], {
				type: 'line',
				width: '100%',
				height: '50',
				lineColor: '#4e44e7',
				fillColor: 'transparent',
				minSpotColor: '#4e44e7',
				maxSpotColor: '#4e44e7',
				spotColor: '#4e44e7',
				highlightLineColor: '#4e44e7',
				highlightSpotColor: '#4e44e7'
			});
		}
	}
	var sparkResize;
/*****Sparkline function end*****/

/*****Resize function start*****/
var sparkResize,echartResize;
$(window).on("resize", function () {
	/*Sparkline Resize*/
	clearTimeout(sparkResize);
	sparkResize = setTimeout(sparklineLogin, 200);
	
	/*E-Chart Resize*/
	clearTimeout(echartResize);
	echartResize = setTimeout(echartsConfig, 200);
}).resize(); 
/*****Resize function end*****/

/*****Function Call start*****/
sparklineLogin();
echartsConfig();
/*****Function Call end*****/