<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
     */
namespace Magento\Ccavenuepay\Test\Unit\Gateway\Response;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Sales\Model\Order\Payment;
use Magento\Ccavenuepay\Gateway\Response\FraudHandler;

class FraudHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandle()
    {
        $response = [
            FraudHandler::FRAUD_MSG_LIST => [
                'Something happened.'
            ]
        ];

        $paymentDO = $this->getMock(PaymentDataObjectInterface::class);
        $paymentModel = $this->getMockBuilder(Payment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $paymentDO->expects(static::once())
            ->method('getPayment')
            ->willReturn($paymentModel);

        $paymentModel->expects(static::once())
            ->method('setAdditionalInformation')
            ->with(
                FraudHandler::FRAUD_MSG_LIST,
                $response[FraudHandler::FRAUD_MSG_LIST]
            );

        $paymentModel->expects(static::once())
            ->method('setIsTransactionPending')
            ->with(true);
        $paymentModel->expects(static::once())
            ->method('setIsFraudDetected')
            ->with(true);

        $request = new FraudHandler();
        $request->handle(['payment' => $paymentDO], $response);

    }
}
