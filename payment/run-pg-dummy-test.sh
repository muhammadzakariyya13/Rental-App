#!/bin/bash

# Run PG Dummy integration test
echo "Running PG Dummy integration test..."
php ./pg-dummy-test.php

# Exit with the status of the test
exit $?
