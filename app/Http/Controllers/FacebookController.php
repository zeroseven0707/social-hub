<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FacebookController extends Controller
{
    public function getAccounts(Request $request)
    {
        // Ganti 'your-user-access-token' dengan User Access Token yang valid
        $accessToken = env('KEY');

        try {
            // Kirim request ke Facebook Graph API
            $response = Http::withToken($accessToken)
                ->get('https://graph.facebook.com/v13.0/me/accounts');

            // Cek jika respon berhasil
            if ($response->successful()) {
                $accounts = $response->json();
                return view('facebook_accounts', ['data' => $response]);
            } else {
                return response()->json(['error' => 'Unable to fetch accounts'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function getPages(Request $request, $id, $token)
    {
        // Ganti 'your-user-access-token' dengan User Access Token yang valid
        $accessToken = $token;

        try {
            // Kirim request ke Facebook Graph API
            $response = Http::withToken($accessToken)
                ->get('https://graph.facebook.com/v13.0/'.$id.'/posts?fields=id,created_time,message,story,full_picture');

            // Cek jika respon berhasil
            if ($response->successful()) {
                $accounts = $response->json();
                // return $response;
                return view('pages', ['data' => $response]);
            } else {
                return response()->json(['error' => 'Unable to fetch accounts'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function dashboard($id)
{
    $accessToken = $id;

    try {
        // Mengirim request ke Facebook Graph API
        $response = Http::withToken($accessToken)
            ->get('https://graph.facebook.com/v21.0/me/insights?metric=page_impressions,page_views_total');

        // Memeriksa apakah respons berhasil
        if ($response->successful()) {
            $accounts = $response->json();

            // Memformat ulang data agar sesuai dengan format yang diinginkan
            $formattedData = [
                "data" => [],
                "paging" => $accounts['paging'] ?? null
            ];

            // Memproses data "data" untuk menambahkan `page_views_total`
            foreach ($accounts['data'] as $metric) {
                // Menambahkan data `page_views_total` jika belum ada
                if ($metric['name'] === 'page_impressions' || $metric['name'] === 'page_views_total') {
                    $formattedData['data'][] = [
                        "name" => $metric['name'],
                        "period" => $metric['period'],
                        "values" => $metric['values'],
                        "title" => $metric['name'] === 'page_impressions'
                            ? ($metric['period'] === 'day' ? 'Daily Total Impressions' : ($metric['period'] === 'week' ? 'Weekly Total Impressions' : '28 Days Total Impressions'))
                            : ($metric['period'] === 'day' ? 'Daily Total views count per Page' : ($metric['period'] === 'week' ? 'Weekly Total views count per Page' : '28 Days Total views count per Page')),
                        "description" => $metric['name'] === 'page_impressions'
                            ? ($metric['period'] === 'day' ? 'Daily: The number of times any content from your Page or about your Page entered a person\'s screen. This includes posts, stories, ads, as well other content or information on your Page. (Total Count)'
                            : ($metric['period'] === 'week' ? 'Weekly: The number of times any content from your Page or about your Page entered a person\'s screen. This includes posts, stories, ads, as well other content or information on your Page. (Total Count)' : '28 Days: The number of times any content from your Page or about your Page entered a person\'s screen. This includes posts, stories, ads, as well other content or information on your Page. (Total Count)'))
                            : 'Daily: Total views count per Page',
                        "id" => $metric['id']
                    ];
                }
            }

            // Mengirim data yang telah diformat ke view
            return view('dashboard', ['data' => $formattedData]);
        } else {
            return response()->json(['error' => 'Unable to fetch accounts'], $response->status());
        }
    } catch (\Exception $e) {
        return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}
public function getInsights(Request $request, $id)
{
    // Access token Facebook
    $accessToken = $id;

    // Metrik insights yang akan diambil dari Facebook
    $metrics = [
        // 'page_engaged_users',
        // 'post_engagements',
        // 'page_fans_online',
        'page_story_adds',
        'page_impressions',
        'page_impressions_unique',
        'page_posts_impressions',
        'page_posts_impressions_organic',
        'page_impressions_paid',
        'page_impressions_viral',
        // 'page_fans_gender_age',
        // 'page_fans_country',
        // 'page_fans_city',
        'page_fans_locale',
        'post_video_views',
        'post_video_views_organic',
        'post_video_views_paid',
        // 'link_clicks',
        'post_clicks',
        'page_actions_post_reactions_like_total',
        'page_actions_post_reactions_love_total',
        'page_actions_post_reactions_wow_total',
        'page_actions_post_reactions_haha_total',
        // 'page_actions_post_reactions_sad_total',
        // 'page_actions_post_reactions_angry_total',
        // 'page_call_phone_clicks',
        // 'page_get_directions_clicks',
        // 'page_website_clicks',
        // 'page_messaging_conversations_started',
        // 'page_cta_clicks_logged_in_total',
        // 'page_total_actions_on_page',
        // 'page_views_login_unique'
    ];

    // Mengirim permintaan ke Facebook Graph API
    $response = Http::withToken($accessToken)
        ->get('https://graph.facebook.com/v21.0/me/insights', [
            'metric' => implode(',', $metrics)
        ]);

    // Cek jika permintaan berhasil
    if ($response->successful()) {
        $insightsData = $response->json();
        // dd($insightsData['data']);
        // Return ke tampilan dengan data insights dan list metrik
        return view('dashboard', [
            'insightsData' => $insightsData['data'],
            'metrics' => $metrics,
            'selectedMetrics' => $request->session()->get('selectedMetrics', $metrics) // Ambil dari session atau tampilkan semua
        ]);
    } else {
        return response()->json(['error' => 'Unable to fetch insights'], $response->status());
    }
}
public function updateMetrics(Request $request)
{
    $selectedMetrics = $request->input('selectedMetrics', []);

    // Simpan pilihan metrik di session
    $request->session()->put('selectedMetrics', $selectedMetrics);

    return back();
}

}
