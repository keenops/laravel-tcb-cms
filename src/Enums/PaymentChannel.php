<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\Enums;

enum PaymentChannel: string
{
    case TcbMobile = 'TCB_MOBILE';
    case TcbBranch = 'TCB_BRANCH';
    case TcbAtm = 'TCB_ATM';
    case Ussd = 'USSD';
    case InternetBanking = 'INTERNET_BANKING';
    case AgentBanking = 'AGENT_BANKING';
    case Pesalink = 'PESALINK';

    public function label(): string
    {
        return match ($this) {
            self::TcbMobile => 'TCB Mobile Banking',
            self::TcbBranch => 'TCB Branch',
            self::TcbAtm => 'TCB ATM',
            self::Ussd => 'USSD Banking',
            self::InternetBanking => 'Internet Banking',
            self::AgentBanking => 'Agent Banking',
            self::Pesalink => 'PesaLink',
        };
    }

    /**
     * Get payment instructions for this channel.
     *
     * @param  string  $referenceNumber  The payment reference number
     * @return string Human-readable payment instructions
     */
    public function getPaymentInstructions(string $referenceNumber): string
    {
        return match ($this) {
            self::TcbMobile => "Open TCB Mobile App > Payments > Bill Payments > Enter Reference: {$referenceNumber}",
            self::TcbBranch => "Visit any TCB Branch and provide Reference Number: {$referenceNumber}",
            self::TcbAtm => "Visit any TCB ATM > Bill Payment > Enter Reference: {$referenceNumber}",
            self::Ussd => "Dial *150*03# > Bill Payments > Enter Reference: {$referenceNumber}",
            self::InternetBanking => "Login to TCB Internet Banking > Payments > Enter Reference: {$referenceNumber}",
            self::AgentBanking => "Visit any TCB Agent and provide Reference Number: {$referenceNumber}",
            self::Pesalink => "Use PesaLink to pay using Reference: {$referenceNumber}",
        };
    }

    /**
     * Get all available payment channels with instructions.
     *
     * @param  string  $referenceNumber  The payment reference number
     * @return array<string, array{label: string, instructions: string}>
     */
    public static function allWithInstructions(string $referenceNumber): array
    {
        $channels = [];

        foreach (self::cases() as $channel) {
            $channels[$channel->value] = [
                'label' => $channel->label(),
                'instructions' => $channel->getPaymentInstructions($referenceNumber),
            ];
        }

        return $channels;
    }
}
