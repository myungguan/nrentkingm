#!/bin/sh

gcc -Wall -O2 -o allat_approval_extra allat_approval_extra.c AllatUtil.c -lssl
gcc -Wall -O2 -o allat_cancel_extra allat_cancel_extra.c AllatUtil.c -lssl
