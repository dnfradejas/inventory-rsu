FROM nginx

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Asia/Manila
WORKDIR /etc/nginx

# RUN rm /etc/nginx/conf.d/default.conf
EXPOSE 80
EXPOSE 443

RUN echo "alias ll='ls -alF'" >> ~/.bashrc

CMD ["nginx", "-g", "daemon off;"]