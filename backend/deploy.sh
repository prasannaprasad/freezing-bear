#!/bin/bash

cd ~/freezing-bear/
rm fb.tgz
tar -cvzf fb.tgz backend/
scp -i ~/pem/freezingbearbackend.pem  fb.tgz ubuntu@ec2-54-187-45-229.us-west-2.compute.amazonaws.com:
ssh -i ~/pem/freezingbearbackend.pem  ubuntu@ec2-54-187-45-229.us-west-2.compute.amazonaws.com './deploy2.sh'
