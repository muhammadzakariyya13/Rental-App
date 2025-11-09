#!/bin/bash

# This script simulates a complete payment flow
# 1. Create a transaction using the test script
# 2. Simulate a webhook callback with a completed payment status

# Define colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[0;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Starting complete payment flow test...${NC}"

# Step 1: Create a transaction
echo -e "${YELLOW}Step 1: Creating a transaction...${NC}"
TRANSACTION_OUTPUT=$(php ./pg-dummy-test-updated.php)

# Extract the VA number from the output using grep
VA_NUMBER=$(echo "$TRANSACTION_OUTPUT" | grep -oP 'VA Number: \K[0-9]+')

if [ -z "$VA_NUMBER" ]; then
    echo -e "${RED}Failed to extract VA number from test output${NC}"
    echo "Transaction output:"
    echo "$TRANSACTION_OUTPUT"
    exit 1
fi

echo -e "${GREEN}Successfully created transaction with VA Number: $VA_NUMBER${NC}"

# Step 2: Simulate a webhook for the transaction with a completed status
echo -e "${YELLOW}Step 2: Simulating webhook for transaction $VA_NUMBER with status 'completed'...${NC}"

# Set the callback URL - use ngrok URL if provided as argument, otherwise use localhost
CALLBACK_URL=${1:-"http://localhost:8000/api/webhook/payment"}

echo -e "${YELLOW}Using callback URL: $CALLBACK_URL${NC}"

# Run the webhook simulation
php ./simulate-webhook.php "$VA_NUMBER" "completed" "$CALLBACK_URL"

# Check if webhook was successful
if [ $? -eq 0 ]; then
    echo -e "${GREEN}Webhook sent successfully! Transaction should now be marked as paid.${NC}"
else
    echo -e "${RED}Webhook sending failed.${NC}"
    exit 1
fi

echo -e "${GREEN}Payment flow test completed successfully!${NC}"
