'use strict';
(function(window) {
	var option;
	var teacherinfo;
	var answer0 = 0; //第一個問題 是否為第一次諮詢
	var nowSlick = 0; //第一頁問問題時左右滑的ppt第幾頁，0~2 3為進入下一階段
	var select = false; //是否時段已被選擇
	var answer1 = []; //問題回答1
	var answer2 = []; //問題回答2
	var answer3 = 0; //0 女生 1 男生 2 都可以
	var answer1str = '';
	var answer2str = '';
	var chooseTaiwanTime; //選中的台灣時間，要用來跟Server溝通的
	var chooseTaiwanDate; //選中的台灣日期，要用來跟server溝通的
	var timeWhichSlick = 0; //目前是哪一個選時間的哪一個slick
	var dateText = []; //選時間表格上排的幾號到幾號 0~3代表4個禮拜的第一天 5~7代表最後一天

	var timeSlick = false; //第二個slide是否已被slick了
	var availableTeacher = []; //可諮詢老師
	var availableTeacherProperty = [];
	var nowTeacherIndex = 0; //目前是可諮詢列表裡的第幾個老師
	var taiwanSchedule = []; //server出來的時間+:00，之後可以用來做時區轉換
	var fee = []; // fee['twd'], fee['usd']
	$(document).ready(function() {

		//----------test----------
		//$('#step3').addClass('show');
		//$('#step-need-help').addClass('show');
		//$('.select-time-slide').slick({
		//infinite:false,
		//arrows:false,
		//draggable:false
		//});
		//$('#step3').addClass('active');
		//$('#step-need-help').addClass('active');
		//moveToStep($('#step2'));
		//----------test----------
		function getOptions() {
			return new Promise(function(resolve) {
				$.ajax({
					url: '../ajax_api_get_options.php', //real
					type: 'GET',
					dataType: 'json',
					success: function(data) {
						option = data;
						//console.log(option);
						data[1].forEach(function(element) {
							$('#question2-option').append(
								'<button class="askforhelp2"><span class="green-word">' +
								element['opt_text'].split('(')[0] +
								'</span> (' +
								element['opt_text'].split('(')[1] +
								'</button>');
						});
						data[2].forEach(function(element) {
							$('#question3-option').append(
								'<button class="askforhelp3">' +
								element['opt_text'] +
								'</button>');
						});
						$('button.askforhelp2').on('click', function() {
							$(this).toggleClass('active');
							answer1 = [];
							$('button.askforhelp2.active').each(function() {
								answer1.push($(this).index());
							});
						});
						$('button.askforhelp3').on('click', function() {
							$(this).toggleClass('active');
							answer2 = [];
							$('button.askforhelp3.active').each(function() {
								answer2.push($(this).index());
							});
						});
						resolve();
					}
				});
			});
		}

		function getCounselor() {
			return new Promise(function(resolve) {
				$.ajax({
					url: '../ajax_api_get_counselor_info.php', //real
					type: 'GET',
					dataType: 'json',
					success: function(data) {
						teacherinfo = data;
						//console.log(teacherinfo);
						var count = 0;
						$.each(teacherinfo, function(index, value) {
							$('div#colelem-choose .container .teacher-info-container').last().append( //把專長經歷都放上去
								'<div class="consultantBlock" data-id="' + index + '">' +
								'<div class="elementchoose">選擇</div>' +
								'<div class="elementphoto" style="background-image:url(../images/' + value.photo + ')"></div>' +
								'<div class="elementchatbox">' +
								'<p class="elementchattitle">專長</p>' +
								'<p class="elementchatcontent">' + value.speciality + '</p>' +
								'<p class="elementchattitle">經歷</p>' +
								'<p class="elementchatcontent">' + value.career + '</p>' +
								'</div>' +
								'<div class="elementname">' + value['whole_name'] + '</div>' +
								'<div class="elementline"></div>' +
								'<div class="elementdescribe">' +
								'<p>' + value['short_words'] + '</p>' +
								'</div>' +
								'</div>'
							);
							if (value.profile !== '')
								$('#page').append( //個人檔案iframe
									'<div class="consultantprofile" data-id="' + index + '">' +
									'<button class="closeprofile"></button>' +
									'<iframe src="./' + value.profile + '" frameborder="0" class="profile"></iframe>' +
									'</div>');
							count++;
							if (count % 12 == 0)
								$('div#colelem-choose .container').append('<div class="teacher-info-container"></div>');

						});
						//為所有新增的元件綁上listener
						$('.elementchoose').on('click', function() {
							availableTeacher = [];
							nowTeacherIndex = 0;
							availableTeacher.push($(this).parent().data('id'));
							$('#colelem-choose').fadeOut();
							$('#colelem-finish-choose').fadeIn();
							scrollTop(80);
							nowTeacherIndex = 0;
							loadConsultantInformation(availableTeacher);
						});
						$('.more-link').on('click', function() {
							if ($('.consultantprofile[data-id="' + getTeacherId() + '"]').length !== 0) {
								$('.consultantprofile[data-id="' + getTeacherId() + '"]').addClass('active');
								$('body').css('overflow-y', 'hidden');
								$('.modal').addClass('active');
							}
						});
						$('.closeprofile').on('click', function() {
							$(this).parent().removeClass('active');
							$('body').css('overflow-y', 'scroll');
							$('.modal').removeClass('active');
						});
						resolve();
					}
				});


			});
		}
		getOptions()
			.then(function() {
				return getCounselor();
			})
			.then(function() {
				$('#step1').addClass('show');
				$('.slide').slick({
					infinite: false,
					arrows: false,
					draggable: false
				});
				$('#step1').addClass('active');
				scrollTop(80);
				$.getJSON('data.json', function(data) {
					$.each(data.timeZone, function(index, value) { //放上時區列表
						if (index === '+8') //預選+8時區
							$('select.choose-abroad').append('<option value="' + index + '" selected="selected">' + value + '</option>');
						else
							$('select.choose-abroad').append('<option value="' + index + '">' + value + '</option>');
					});
				});
			});

		//--------------step1--------------
		$('.slide-next').on('click', function() {
			for (var i = 1; i <= 2; i++)
				if (nowSlick === i && $('.askforhelp' + (i + 1) + '.active').length === 0) {
					$('.not-answer-question.question' + i).showHint();
					return;
				}

			$('.slide').slick('slickNext');
			if (nowSlick < 3)
				nowSlick++;
			switch (nowSlick) {
				case 0:
					$('.slide-next').addClass('hide');
					$('.slide-prev').addClass('hide');
					break;
				case 3:
					$('.slide-next').addClass('hide');
					break;
				case 1:
				case 2:
					$('.slide-next').removeClass('hide');
					$('.slide-prev').removeClass('hide');
					break;
			}
		});
		$('.slide-prev').on('click', function() {
			$('.slide').slick('slickPrev');
			if (nowSlick > 0)
				nowSlick--;

			switch (nowSlick) {
				case 0:
					$('.slide-next').addClass('hide');
					$('.slide-prev').addClass('hide');
					break;
				case 1:
				case 2:
					$('.slide-next').removeClass('hide');
					$('.slide-prev').removeClass('hide');
					break;
			}
		});
		$('#askforhelpyes').on('mouseup', function() { //第一次進來
			answer0 = 1;
			$('.slide-next').trigger('click');
		});
		$('#askforhelpno').on('mouseup', function() { //不是第一次進來
			answer0 = 0;
			$('.slide-next').trigger('click');
		});
		$('#askforhelpchoose').on('mouseup', function() { //不是第一次進來
			answer0 = 2;
			$('#colelem-slide').fadeOut();
			$('#colelem-choose').fadeIn();
		});
		$('button.askforhelp4').on('click', function() {
			answer3 = $(this).index();
			$('button.askforhelp4').removeClass('active');
			$(this).addClass('active');
			$('button.askforhelp-analyze').addClass('active');
		});
		$('button.askforhelp-analyze').on('click', function() {
			if ($('.askforhelp4.active').length === 0) {
				return;
			} else
				toFinal();
		});

		$('.choose-card-prev-btn').on('click', function() {
			if (nowTeacherIndex === 0)
				return;
			nowTeacherIndex = (nowTeacherIndex - 1) % availableTeacher.length;
			flipChooseCard();
			changeChooseProfile(getTeacherId());
		});
		$('.choose-card-next-btn').on('click', function() {
			if (nowTeacherIndex === availableTeacher.length - 1)
				return;
			nowTeacherIndex = (nowTeacherIndex + 1) % availableTeacher.length;
			flipChooseCard();
			changeChooseProfile(getTeacherId());
		});

		$('#step1 #colelem-choose .next-step').on('click', function() {
			$('#colelem-choose').fadeOut();
			$('#colelem-slide').fadeIn();
		});
		$('#step1 #colelem-choose .next-info').on('click', function() {
			if ($('.teacher-info-container.active').next().length !== 0)
				$('.teacher-info-container.active').removeClass('active').next().addClass('active');
		});
		$('#step1 #colelem-choose .prev-info').on('click', function() {
			if ($('.teacher-info-container.active').prev().length !== 0)
				$('.teacher-info-container.active').removeClass('active').prev().addClass('active');
		});
		$('#step1 #colelem-choose .return-slide').on('click', function() {
			$('#colelem-choose').fadeOut();
			$('#colelem-slide').fadeIn();
		});
		$('#step1 #colelem-finish-choose .prev-step').on('click', function() {
			$('.choose-card-div').empty();
			$('#colelem-finish-choose').fadeOut();
			$('#colelem-slide').fadeIn();
			$('.slide').slick('slickGoTo', 1, true);
			nowSlick = 1;
			$('.slide-next').removeClass('hide');
			$('.slide-prev').removeClass('hide');
		});

		$('#step1 #colelem-finish-choose .next-step').on('click', function() { //確認
			var tmpDate, i;
			for (i = 0; i < 4; i++) { //將選時間表上的日期和星期設定好
				tmpDate = getTaiwanDay(i * 7);
				dateText.push(tmpDate.substr(0, 10) + '(' + getWeekDay(tmpDate.substr(11, 1)) + ')');
			}
			for (i = 0; i < 4; i++) {
				tmpDate = getTaiwanDay(i * 7 + 6);
				dateText.push(tmpDate.substr(0, 10) + '(' + getWeekDay(tmpDate.substr(11, 1)) + ')');
			}
			$('#step2 .date.prev').html(dateText[0]);
			$('#step2 .date.next').html(dateText[4]);

			for (i = 0; i < 28; i++) {
				tmpDate = getTaiwanDay(i);
				$('#step2 .week-name .col').eq(i).html(tmpDate.substr(5, 2) + '/' + tmpDate.substr(8, 2) + '(' + getWeekDay(tmpDate.substr(11, 1)) + ')');
				$('#step2 .week-name .col').eq(i).data('date', tmpDate);
			}

			//-------老師日期ajax-------
			setTeacherSchedule();

			//挑選時間
			$('#step1').removeClass('show').removeClass('active');
			moveToStep($('#step2'));

			//put answer
			answer1str = '';
			answer2str = '';
			$('.choose-teacher-slide').remove(); //reset 可以選的老師
			$('.prev-teacher-btn').after('<div class="choose-teacher-slide"></div>');
			availableTeacherProperty = [];
			availableTeacher.forEach(function(value, index) {
				$('.choose-teacher-slide').append(
					'<div class="grey-container1">' +
					'<div class="content content1">' +

					'<div class="column1 col">' +
					'<div class="title">預約諮詢師</div>' +
					'<div class="content">' +
					'<div class="col1 col">' +
					'<div class="photo"></div>' +
					'<div class="name-block">' +
					'<div class="name">' + teacherinfo[value]['name_ch'] + '</div><br/>' +
					'<div class="profession">心理諮詢師</div>' +
					'</div>' +
					'</div>' +
					'</div>' +
					'</div>' +
					// '<div class="column2 col">' +
					// '<div class="title">協助傾向</div>' +
					// '<div id="need-help"></div>' +
					// '</div>' +
					// '<div class="column3 col">' +
					// '<div class="title">困擾狀況</div>' +
					// '<div id="sad-situation"></div>' +
					// '</div>' +
					// '<div class="column4 col">' +
					// '<div class="title">諮詢堂數</div>' +
					// '<div>1堂課</div>' +
					// '<div>(50分鐘/堂)</div>' +
					// '</div>' +
					// '<div class="column5 col">' +
					// '<div class="title">選項</div>' +
					// '<div class="choose-again">' +
					// '重選' +
					// '</div>' +
					'</div>' +

					'</div>' +
					'</div>'
					// '<div class="grey-container1">' +
					// '<div class="title">' +
					// '<div class="col1 col">預約諮詢師</div>' +
					// '<div class="col2 col">協助傾向</div>' +
					// '<div class="col3 col">困擾狀況</div>' +
					// '<div class="col4 col">諮詢堂數</div>' +
					// '<div class="col5 col">選項</div>' +
					// '</div>' +
					// '<div class="content">' +
					// '<div class="col1 col">' +
					// '<div class="photo"></div>' +
					// '<div class="name-block">' +
					// '<div class="name">' + teacherinfo[value]['name_ch'] + '</div><br/>' +
					// '<div class="profession">心理諮詢師</div>' +
					// '</div>' +
					// '</div>' +
					// '<div class="col2 col" id="need-help"></div>' +
					// '<div class="col3 col" id="sad-situation"></div>' +
					// '<div class="col4 col">' +
					// '<div>1堂課</div>' +
					// '<div>(50分鐘/堂)</div>' +
					// '</div>' +
					// '<div class="col5 col">' +
					// '<div class="choose-again">' +
					// '重選' +
					// '</div>' +
					// '</div>' +
					// '</div>' +
					// '</div>'
				);
				if (teacherinfo[value].photo !== '')
					$('#step2 .grey-container1 .content .photo ').eq(index).css('background-image', 'url(../images/' + teacherinfo[value].photo + ')');

				//替老師資料建表
				availableTeacherProperty.push({
					name: teacherinfo[value]['name_ch'],
					photo: 'url(../images/' + teacherinfo[value].photo + ')'
				});
			});
			$('.step-container .content .choose-again').on('click', function() { //contain both step2 step3 choose again
				flipChooseCard();
				moveToStep.call(this, $('#step1'));
				scrollTop(80);
			});
			$('.choose-teacher-slide').slick({
				infinite: false,
				arrows: false,
				draggable: false
			});
			$('.choose-teacher-slide').on('swipe', function(event, slick, direction) {
				if (direction === 'left' && nowTeacherIndex !== availableTeacher.length - 1) {
					nowTeacherIndex++;
					setTeacherSchedule();
				} else if (direction === 'right' && nowTeacherIndex !== 0) {
					nowTeacherIndex--;
					setTeacherSchedule();
				}
			});


			//處理答案
			answer1.forEach(function(value, index) {
				answer1str += option[1][value]['opt_text'];
				if (index !== answer1.length - 1)
					answer1str += '、';
			});
			$('.step-container #need-help').html(answer1str);
			answer2.forEach(function(value, index) {
				answer2str += option[2][value]['opt_text'];
				if (index !== answer2.length - 1)
					answer2str += '、';
			});

			$('.step-container #sad-situation').html(answer2str);
			$('.choose-teacher-slide').slick('slickGoTo', nowTeacherIndex, true);
			if (!timeSlick) {
				$('.select-time-slide').slick({
					infinite: false,
					arrows: false,
					draggable: false
				});
				timeSlick = true;
			}
			$('.select-time-slide').slick('slickGoTo', 0, true); //防止重選的情況，事先把選時間表滑回原本第一頁

		});

		//--------------step2--------------
		$('input[name="country-radio"]').change(function() { //更換時區
			if ($('input#abroad-radio').prop('checked') === true) {
				$('select.choose-abroad').prop('disabled', false);
				setSchedule(taiwanSchedule, Number($('select.choose-abroad').val()) - 8);
				$('.time-zone').html('( 時區: UTC ' + $('select.choose-abroad').val() + ' )');
			} else {
				$('select.choose-abroad').prop('disabled', true);
				setSchedule(taiwanSchedule, 0);
				$('.time-zone').html('( 時區: UTC +8 )');
			}
		});
		$('select.choose-abroad').change(function() {
			setSchedule(taiwanSchedule, Number($('select.choose-abroad').val()) - 8);
			$('.time-zone').html('( 時區: UTC ' + $('select.choose-abroad').val() + ' )');
		});

		$('.time-icon.next').on('click', function() { //列表上一張和下一張
			$('.select-time-slide').slick('slickNext');
			if (timeWhichSlick !== 3)
				timeWhichSlick++;
			$('#step2 .date.prev').html(dateText[timeWhichSlick]);
			$('#step2 .date.next').html(dateText[timeWhichSlick + 4]);
		});
		$('.time-icon.prev').on('click', function() {
			$('.select-time-slide').slick('slickPrev');
			if (timeWhichSlick !== 0)
				timeWhichSlick--;
			$('#step2 .date.prev').html(dateText[timeWhichSlick]);
			$('#step2 .date.next').html(dateText[timeWhichSlick + 4]);
		});

		$('#step2 .prev-step').on('click', function() { //contain both step2 step3 choose again
			flipChooseCard();
			moveToStep.call(this, $('#step1'));
			scrollTop(80);
		});

		$('#step2 .next-step').on('click', function() { //下一步
			if ($('.select-date').html() === '')
				$('.no-book-time-hint').showHint();
			else {
				moveToStep.call(this, $('#step3'));
				var str = $('.select-date').html();
				$('#step3 .grey-container1 .name-block .name').html(getSelectTeacherName());
				$('#step3 .grey-container1 .photo').css('background-image', getSelectTeacherPhoto());
				$('#book-confirm .content[name=counselor]').html('預約諮詢師：' + getSelectTeacherName());
				$('#book-confirm .content[name=time]').html('預約時間：' + str.substr(5, 5) + '(' + str.substr(13, 1) + ') ' + str.substr(15, 5) + '(UTC' + $('.grey-container3 .time-zone').html().slice(9, -1) + ')');
			}
		});
		$('#step2 .step-div a').on('click', function() { //下一步
			scrollTop(1240);
		});
		$('.prev-teacher-btn').on('click', function() {
			$('.choose-teacher-slide').slick('slickPrev');
			if (nowTeacherIndex !== 0) {
				nowTeacherIndex--;
				setTeacherSchedule();
			}
		});
		$('.next-teacher-btn').on('click', function() {
			$('.choose-teacher-slide').slick('slickNext');
			if (nowTeacherIndex !== availableTeacher.length - 1) {
				nowTeacherIndex++;
				setTeacherSchedule();
			}
		});

		//--------------step3--------------
		$('#step3 #colelem2 .prev-step').on('click', function() {
			moveToStep.call(this, $('#step2'));
		});
		$('input#usd-radio').change(function() {
			$('div.payment-div').removeClass('active');
			if (this.checked) {
				$('div.usd-payment').addClass('active');
				payway = 0;
			}
		});
		$('input#rmb-radio').change(function() {
			$('div.payment-div').removeClass('active');
			if (this.checked) {
				$('div.rmb-payment').addClass('active');
				payway = 1;
			}
		});
		$('#step3 #colelem2 .next-step').on('click', function() {
			if (!$('#agreement-checkbox').prop('checked')) //同意書沒同意
				return $('#agreement-hint').showHint();

			$('#step3 #book-confirm').addClass('active');
			$('.modal').addClass('active');
		});
		$('#step3 #book-confirm .prev-step').on('click', function() {
			$('#step3 #book-confirm').removeClass('active');
			$('.modal').removeClass('active');
		});
		$('#step3 #coupon_confirm').on('click', function() {
			if ($('#coupon_input').val() == 'kajin2016') {
				fee.twd = fee.twd * 0.8;
				fee.usd = fee.usd * 0.8;
				$(this).after('已使用優惠代碼，您本次諮詢費用為美金' + fee.usd / 100 + '元。')
			}
		});
		$('#step3 #book-confirm .next-step').on('click', function() {
			$(this).parent().children().prop('disabled', true);
			var self = this;
			var handler = StripeCheckout.configure({
				//key: 'pk_test_6pRNASCoBOKtIshFeQd4XMUh',
				key: 'pk_live_jL7Bu4XKJxHi7uQh7eqzPde9',
				image: './images/kajin.png',
				locale: 'auto',
				alipay: true,
				closed: function() {
					$(self).parent().children().prop('disabled', false);
					setTimeout(function() {
						$('iframe.stripe_checkout_app').remove();
					}, 100);
				},
				token: function(res) {
					$.ajax({
						url: '../ajax_api_start_pay.php', //real
						type: 'POST',
						data: {
							counselor_id: getTeacherId(),
							date: chooseTaiwanDate,
							time: chooseTaiwanTime,
							token: res.id,
							news: $('#enews-checkbox').prop('checked') ? true : false,
							fee: fee,
							timezone: Number($('select.choose-abroad').val())
						},
						dataType: 'json',
						success: function(data) {
							$(self).parent().children().prop('disabled', false);
							$('.modal').removeClass('active');
							$('#step3 #book-confirm').removeClass('active');
							if (data.error !== 1) {
								$('#counselor_name').val(getSelectTeacherName());
								$('#counselor_photo').val(getSelectTeacherPhoto());
								$('#need_help').val(answer1str);
								$('#sad_situation').val(answer2str);
								$('#select_date').val($('.select-date').html());
								$('#time_zone').val($('.time-zone').html());
								$('#finish_form').submit();
							} else
								moveToStep.call(self, $('#step-error'));
						}
					});
				}
			});
			handler.open({
				name: 'Kajin Health',
				description: '預約付款：美金' + (fee.usd / 100) + '元',
				amount: fee.usd
			});
		});
		$('.book-title.need-help button').on('click', function() {
			moveToStep.call(this, $('#step-need-help'));
		});

		//------------step error-----------
		$('#step-error .prev-step').on('click', function() {
			moveToStep.call(this, $('#step3'));
		});
		//------------step need-help-----------
		$('#step-need-help #colelem2 .prev-step').on('click', function() {
			moveToStep.call(this, $('#step3'));
		});
		$('#step-need-help #colelem2 .next-step').on('click', function() {
			$('#step-need-help .step-div .prev-step').attr('disabled', true);
			$('#step-need-help .step-div .next-step').attr('disabled', true);
			$.ajax({
				url: '../ajax_api_need_help.php', //real
				type: 'POST',
				data: {
					time: Number($('#step-need-help select').val())
				},
				dataType: 'json',
				success: function() {
					$('#need-help-confirm').addClass('active');
					$('.modal').addClass('active');
				}
			});
		});
		$('#step-need-help .hint-greybox .to-panel').on('click', function() {
			window.location.href = 'https://kajinonline.com/panel.php';
		});
	});


	//-----------------util-----------------
	$.fn.showHint = function() { //hint類的，用這個函數來顯示
		$(this).addClass('active');
		var self = this;
		setTimeout(function() {
			$(self).removeClass('active');
		}, 5000);
	};
	$.fn.fadeIn = function() {
		$(this).addClass('show');
		var self = this;
		setTimeout(function() {
			$(self).addClass('active');
		}, 10);
	};
	$.fn.fadeOut = function() {
		$(this).removeClass('active').removeClass('show');
	};

	//-----------------setTeacherSchedule-----------------
	function getWeekDay(num) { //得到今天星期幾
		if (typeof num !== 'number')
			num = Number(num);

		if (num === 1)
			return '一';
		else if (num === 2)
			return '二';
		else if (num === 3)
			return '三';
		else if (num === 4)
			return '四';
		else if (num === 5)
			return '五';
		else if (num === 6)
			return '六';
		else if (num === 0)
			return '日';
	}

	function getTaiwanDay(shiftDay) { //shiftDay 是距今幾天整，今天為0
		return moment().utcOffset(8).add(shiftDay + '', 'days').format('YYYY-MM-DD-d'); //取得目前台灣日期 年-月-日-星期
	}

	function setSchedule(schedule, shift) { //設定時間表

		//reset 所有內容
		$('#step2 .can-book-time .col').html('');
		$('#step2 .select').removeClass('select');
		$('.select-date').html('');

		var tmpSchedule = $.extend(true, {}, schedule);
		var taiwanDate = []; //用來儲存台灣時間，等一下要一個一個map到時間小格上
		$.each(tmpSchedule, function(index) {
			tmpSchedule[index] = [];
		});
		$.each(schedule, function(index, value) {
			value.forEach(function(element) {
				var tmpTime = moment(index + ' ' + element).add(shift, 'hours').format(); //YYYY-MM-DDThh:mm:ss+08:00 雖然是顯示台灣時區，不過只要前面的部分，後面的時區不重要。
				tmpSchedule[tmpTime.substr(0, 10)] && tmpSchedule[tmpTime.substr(0, 10)].push(tmpTime.substr(11, 5));
				taiwanDate.push(index + ' ' + element);
			});
		});

		var which = 0;
		var taiwanTimeWhich = 0;
		$.each(tmpSchedule, function(index, value) {
			value.forEach(function(element) {
				$('#step2 .can-book-time .col').eq(which).append('<div class="book-time-small-block" data-taiwantime="' + taiwanDate[taiwanTimeWhich] + '">' + element + '</div>');
				taiwanTimeWhich++;
			});
			which++;
		});

		$('.book-time-small-block').on('click', function() {
			$('.week-name .col.select').removeClass('select');
			$('.can-book-time .col.select').removeClass('select');
			if (select === false) //沒有時段被選到
				select = true;
			else if (select === true && $(this).hasClass('select')) { //目前點的時段是已經被選擇的
				select = false;
				$(this).toggleClass('select');
				$('.select-date').html('');
				return;
			} else if (select === true && !$(this).hasClass('select')) //目前點的時段沒被選擇到，不過有其它時段已被選擇了
				$('.book-time-small-block.select').removeClass('select');
			$(this).toggleClass('select');

			var column = Number($('.book-time-small-block.select').parent().prop('className').substr(7, 1));
			$(this).parent().addClass('select');
			$(this).parents('.can-book-time').siblings('.week-name').find('.col' + column).addClass('select');
			var selectDate = $('#step2 .week-name .col.select').data('date');
			$('.select-date').html(selectDate.substr(0, 10) + ' 星期' + getWeekDay(selectDate.substr(11, 1)) + ' ' + $(this).html());
			chooseTaiwanDate = $(this).data('taiwantime').substr(0, 10);
			chooseTaiwanTime = Number($(this).data('taiwantime').substr(11, 2));
		});
	}

	function setTeacherSchedule() {
		$.ajax({
			url: '../ajax_api_start_get_available.php',
			type: 'POST',
			data: {
				counselor_id: getTeacherId(),
				date: getTaiwanDay(0).substr(0, 10),
				range: 28
			},
			dataType: 'json',
			success: function(data) {
				if (data.error !== 1) {
					taiwanSchedule = $.extend(true, {}, data.slot);
					$.each(taiwanSchedule, function(index, value) {
						var tmp = [];
						value.forEach(function(element) {
							tmp.push(element + ':00');
						});
						taiwanSchedule[index] = tmp;
					});
					//reset時區
					$('input#taiwan-radio').prop('checked', true);
					setSchedule(taiwanSchedule, 0);
					fee = data.fee;
				}
			}
		});
	}
	//-----------------setTeacherSchedule-----------------
	function loadConsultantInformation(data) {
		$('#colelem-finish-choose span.number').html(data.length);

		function appendConsultant(id) {
			if (id === 0)
				$('.choose-card-div').append('<div class="consultantBlock none"></div>');
			else
				$('.choose-card-div').append(
					'<div class="consultantBlock">' +
					'<div class="elementphoto" style="background-image:url(../images/' + teacherinfo[id].photo + ');"></div>' +
					'<div class="elementname">' + teacherinfo[id]['whole_name'] + '</div>' +
					'<div class="elementline"></div>' +
					'</div>');
		}
		appendConsultant(0);
		appendConsultant(0);
		data.forEach(function(element) {
			appendConsultant(element);
		});
		appendConsultant(0);
		appendConsultant(0);
		for (var i = 0; i < 5; i++)
			$('.choose-card-div .consultantBlock').eq(i).addClass('active' + i);
		changeChooseProfile(getTeacherId());
	}

	function scrollTop(where) { //滑到頁面上哪一個pixel
		$('html, body').animate({
			scrollTop: where
		}, 1500, 'easeOutExpo');
	}

	function moveToStep($step) { //移到哪一頁
		$(this).parents('.step-container').removeClass('show').removeClass('active');
		$(this).parents('.step-container').find('.side-hint').removeClass('active');
		$step.addClass('show');
		setTimeout(function() {
			$step.addClass('active');
		}, 0);
		$step.find('.side-hint').addClass('active');
		scrollTop(80);
	}

	function flipChooseCard() {
		for (var i = 0; i < 5; i++)
			$('.choose-card-div .consultantBlock.active' + i).removeClass('active' + i);
		for (i = nowTeacherIndex; i < nowTeacherIndex + 5; i++)
			$('.choose-card-div .consultantBlock').eq(i).addClass('active' + (i - nowTeacherIndex));
	}

	function changeChooseProfile(id) {
		$('.choose-profile .elementchatcontent.speciality').html(teacherinfo[id].speciality);
		$('.choose-profile .elementchatcontent.career').html(teacherinfo[id].career);
	}

	function toFinal() { //by answer question
		$('#colelem-slide').fadeOut();
		$('#colelem-load').fadeIn();
		//-------選擇老師ajax-------
		$.ajax({
			url: '../ajax_api_start_get_counselor.php',
			type: 'POST',
			data: {
				answer0: answer0,
				answer1: answer1,
				answer2: answer2,
				answer3: answer3
			},
			dataType: 'json',
			success: function(data) {
				//if(data.error===1 && data.error_type==='NOT_LOGIN')
				//console.log('Error:NOT_LOGIN');
				if (data.error !== 1) {
					availableTeacher = data;
					nowTeacherIndex = 0;
					setTimeout(function() {
						$('#colelem-load').fadeOut();
					}, 6000);
					setTimeout(function() {
						$('#colelem-finish-choose').fadeIn();
						scrollTop(80);
					}, 6010);
					loadConsultantInformation(availableTeacher);
				}
			}
		});
		//-------選擇老師ajax-------
	}

	function getTeacherId() {
		return availableTeacher[nowTeacherIndex];
	}

	function getSelectTeacherName() {
		return availableTeacherProperty[nowTeacherIndex].name;
	}

	function getSelectTeacherPhoto() {
		return availableTeacherProperty[nowTeacherIndex].photo;
	}

	(function() {
		//側邊的hint
		window.sr = ScrollReveal();
		sr.reveal('.step2-book-item-side-hint', {
			distance: '50px',
			viewFactor: 1.5,
			viewOffset: {
				bottom: 200
			},
			reset: true
		});
		sr.reveal('.step2-book-time-side-hint', {
			distance: '50px',
			viewFactor: 1.5,
			viewOffset: {
				bottom: 250
			},
			reset: true
		});
		sr.reveal('.step3-book-item-side-hint', {
			distance: '50px',
			viewFactor: 1.5,
			viewOffset: {
				bottom: 200
			},
			reset: true
		});
		sr.reveal('.step3-book-time-side-hint', {
			distance: '50px',
			viewFactor: 1.5,
			viewOffset: {
				bottom: 300
			},
			reset: true
		});
		sr.reveal('.step3-payment-side-hint', {
			distance: '50px',
			viewFactor: 1.5,
			viewOffset: {
				bottom: 300
			},
			reset: true
		});
		sr.reveal('.step3-other-side-hint', {
			distance: '50px',
			viewFactor: 1.5,
			viewOffset: {
				bottom: 250
			},
			reset: true
		});
	})();








})(window);
