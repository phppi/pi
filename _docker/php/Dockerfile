FROM webgarden/php:7.1

ADD injector.sh /tmp/injector.sh
RUN chmod +x /tmp/injector.sh \
	&& sh /tmp/injector.sh \
	&& rm /tmp/injector.sh
