	var neonChat = new Object;
	var $chat = $("#chat"),
		$chat_inner = $chat.find('.chat-inner'),
		$chat_badge = $chat.find('.badge').add($('.chat-notifications-badge')),
		$conversation_window = $chat.find(".chat-conversation"),
		$conversation_header = $conversation_window.find(".conversation-header"),
		$conversation_body = $conversation_window.find('.conversation-body'),
		$textarea = $conversation_window.find('.chat-textarea textarea');
	
	neonChat.sidebar_default_is_open = ! $(".page-container").hasClass('sidebar-collapsed');
	
	/**
	 * Genera la inicializacion de de las funciones correspondientes
	 **/
	neonChat.init = function() {
		$("body").on('click', '[data-toggle="chat"]', function(ev) {
			ev.preventDefault();
			var $this = $(this),
			with_animation = $this.is('[data-animate]'),
			collapse_sidebar = $this.is('[data-collapse-sidebar]');
			neonChat.toggleChat(with_animation, collapse_sidebar);
		});
		
		$("body").on('click', '.chat-close', function(ev) {
			ev.preventDefault();
			var animate = $(this).is('[data-animate]');
			neonChat.hideChat(animate);
		});
		
		$chat.on('click', '.chat-group a', function(ev) {
			ev.preventDefault();
			var $chat_user = $(this);
		
			if($chat_user.hasClass('active')) {
				neonChat.close();
			}
			else {
				neonChat.open($chat_user);
			}
		});
	};
	
	neonChat.toggleChat = function(animated, collapse_sidebar) {
		var _func = public_vars.$pageContainer.hasClass('chat-visible') ? 'hideChat' : 'showChat';
		if(isxs()) {
			_func = public_vars.$pageContainer.hasClass('toggle-click') ? 'hideChat' : 'showChat';
		}
		neonChat[_func](animated);
		
		if(collapse_sidebar) {
			if(neonChat.sidebar_default_is_open) {
				// Hide Sidebar
				if(_func == 'hideChat') {
					show_sidebar_menu(animated);
				}
				else {
					hide_sidebar_menu(animated);
				}
			}
		}
	};
	
	neonChat.showChat = function (animated) {
		var visible_class = 'chat-visible';
		
		if(isxs()) {
			visible_class += ' toggle-click';
		}
		
		if(animated) {
			if($chat.data('is-busy')) {
				return false;
			}
		
			$chat.data('is-busy', true);
		
			public_vars.$pageContainer.addClass(visible_class);
		
			var mc_padding_right = parseInt(public_vars.$mainContent.css('padding-right'), 10),
				mc_padding_left = parseInt(public_vars.$mainContent.css('padding-left'), 10),
				chat_width = $chat.width();
		
			public_vars.$pageContainer.removeClass(visible_class);
		
			if(public_vars.$pageContainer.hasClass('right-sidebar')) {
				public_vars.$mainContent.transition({
					paddingLeft: mc_padding_left,
					duration: 500
				});
			}
			else {
				public_vars.$mainContent.transition({
					paddingRight: mc_padding_right,
					duration: 500
				});
			}
		
			$chat.show();
			TweenMax.set($chat, {css: {autoAlpha: public_vars.$body.hasClass('boxed-layout') ? 0 : 1, transform: "translateX("+((public_vars.$pageContainer.hasClass('right-sidebar') ? -1 : 1) * chat_width)+"px)"}});
		
			TweenMax.to($chat, .65, {css: {autoAlpha: 1, transform: "translateX(0)"}, ease: Sine.easeOut, onComplete: function() {
				public_vars.$pageContainer.addClass(visible_class);
				public_vars.$mainContent.attr('style', '');
		
				TweenMax.set($chat, {css: {transform: '', paddingRight: '', paddingLeft: ''}});
		
				$chat.data('is-busy', false);
			}});
		}
		else {
			public_vars.$pageContainer.addClass(visible_class);
		}
	};
	
	/**
	 *Genera la opcion de abrir o cerrar el sidebar de chat
	 **/
	neonChat.hideChat = function(animated) {
		var visible_class = 'chat-visible';
		
		if(isxs()) {
			visible_class += ' toggle-click';
		}
		public_vars.$pageContainer.removeClass(visible_class);
	};
	
	neonChat.open = function($user_link) {
		if(typeof $user_link == 'string') {
			$user_link = $($user_link);
		}
		
		// Set Active Class
		$chat.find('.chat-group a').not($user_link).removeClass('active');
		$user_link.addClass('active');
		
		// Chat Header
		var user_status = 1;
		
		
		$conversation_header.find('.display-name').html($user_link.find('em').text());
		
		if(user_status) {
			$conversation_header.find('.user-status').attr('class', 'user-status ' + user_status.class);
			$conversation_header.find('small').html(user_status.label);
		}
		
		$conversation_window.show();
		$textarea.val('');
		
		$textarea.focus();
	};
		
	neonChat.close = function() {
		$chat.find('.chat-group a').removeClass('active');
		$conversation_window.hide().css({top: 0, opacity: 0});
		$conversation_body.find('li.unread').removeClass('unread');
		
		return false;
	};
	
	
	neonChat.init();