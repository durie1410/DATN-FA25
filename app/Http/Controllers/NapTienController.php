<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NapTien;
use Illuminate\Support\Str;

class NapTienController extends Controller
{
    public function form()
    {
        return view('frontend.nap-tien');
    }

    public function momoPayment(Request $request)
    {
        $so_tien = $request->so_tien;
$readerId = \App\Models\Reader::where('user_id', auth()->id())->value('id');
        $maGD = Str::uuid();

        $nap = NapTien::create([
            'reader_id' => $readerId,
            'so_tien' => $so_tien,
            'ma_giao_dich' => $maGD,
        ]);

        // MoMo sandbox info
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
$partnerCode = "MOMO";
$accessKey   = "F8BBA842ECF85";
$secretKey   = "K951B6PE1waDMi640xX08PD3vg6EkVlz";

        $orderInfo = "Nap tien vao tai khoan doc gia";
        $amount = $so_tien;
        $orderId = $maGD;
        $redirectUrl = route('nap-tien.callback');
        $ipnUrl = route('nap-tien.ipn');
        $requestId = $maGD;
        $requestType = "captureWallet";
        $extraData = "";

        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "MoMo",
            'storeId' => "MoMoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];
$result = $this->execPostRequest($endpoint, json_encode($data));
$jsonResult = json_decode($result, true);

// Thêm đoạn debug này
if (!isset($jsonResult['payUrl'])) {
    dd($jsonResult); // In ra phản hồi từ MoMo để xem lỗi gì
}

return redirect($jsonResult['payUrl']);

    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function callback(Request $request)
    {
        if ($request->resultCode == 0) {
            $nap = NapTien::where('ma_giao_dich', $request->orderId)->first();
            if ($nap && $nap->trang_thai != 'thanh_cong') {
                $nap->update(['trang_thai' => 'thanh_cong']);
                $nap->reader->increment('so_du', $nap->so_tien);
            }
            return redirect()->route('nap-tien.thanhcong')->with('success', 'Nạp tiền thành công!');
        } else {
            return redirect()->route('nap-tien.thatbai')->with('error', 'Giao dịch thất bại!');
        }
    }
}

