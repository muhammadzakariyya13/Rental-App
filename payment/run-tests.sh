#!/bin/bash
# This script runs all payment tests from the payment directory

# Define colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[0;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}============================================${NC}"
echo -e "${BLUE}PAYMENT GATEWAY TEST SUITE${NC}"
echo -e "${BLUE}============================================${NC}"

# Check if we're in the payment directory
if [ ! -f "direct-gateway-test.php" ]; then
    echo -e "${RED}Error: Run this script from the payment directory${NC}"
    exit 1
fi

# Run direct gateway test
echo -e "${YELLOW}Running Direct Gateway Test...${NC}"
php ./direct-gateway-test.php
echo -e "${GREEN}Direct Gateway Test completed${NC}"
echo

# Run PG Dummy test
echo -e "${YELLOW}Running PG Dummy Integration Test...${NC}"
php ./pg-dummy-test-updated.php
echo -e "${GREEN}PG Dummy Integration Test completed${NC}"
echo

# Ask if user wants to test complete payment flow
echo -e "${YELLOW}Do you want to test the complete payment flow with webhook? (y/n)${NC}"
read -r run_payment_flow

if [[ $run_payment_flow == "y" || $run_payment_flow == "Y" ]]; then
    echo -e "${YELLOW}Enter callback URL (leave empty for default http://localhost:8000/api/webhook/payment):${NC}"
    read -r callback_url
    
    if [ -z "$callback_url" ]; then
        # Run payment flow test with default URL
        ./test-payment-flow.sh
    else
        # Run payment flow test with custom URL
        ./test-payment-flow.sh "$callback_url"
    fi
fi

echo -e "${BLUE}============================================${NC}"
echo -e "${BLUE}PAYMENT TESTS COMPLETED${NC}"
echo -e "${BLUE}============================================${NC}"
