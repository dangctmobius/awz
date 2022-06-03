<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;

class CashbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list_banner($type, Request $request)
    {
        if($type == 'asia') {
            $data['items'] = [
               ['image_url' => 'https://media-img-proxy.shopback.sg/TZyEPnB0iVk/czM6Ly9tZWRpYS1zZXJ2aWNlLXNiLXByb2Qtc2cvMThmMWJkNGItMjBhYS00ZTJmLTlkMzgtNjE0MWUzYjA4YmFkLUhlcm8gTW9iaWxlICgyKS5wbmc.png', 'tracking_url' => 'https://app.azworl.network'],
               ['image_url' => 'https://media-img-proxy.shopback.sg/anqQmyoyvhA/czM6Ly9tZWRpYS1zZXJ2aWNlLXNiLXByb2Qtc2cvNTlkZDBkYTEtNzQxZS00NTY4LWFkMDgtODk4MTk4ODg0YTU3LUhlcm8gTW9iaWxlICgxKS5wbmc.png', 'tracking_url' => 'https://app.azworl.network'],
               ['image_url' => 'https://media-img-proxy.shopback.sg/0LXJoBHo5Ic/aHR0cHM6Ly9pbWFnZXMuYmFubmVyYmVhci5jb20vZGlyZWN0L3ZXbng3cE1iYjRZTUpHTFZtRS9yZXF1ZXN0cy8wMDAvMDE0LzcwMC80NDEvS1BiZWdwNG5vUWtyeUxYTTZsVmtOMDN2RS9hZTM2ODI5MTJhYjJiMzk3ZmI1ZTkyOTQ0MDcwMWIyOGRlYmUxNTMxLnBuZw.png', 'tracking_url' => 'https://app.azworl.network']
            ];
            return $this->responseOK($data, 'success');
        } else if($type == 'global_slider_2') {

        //    $data['items']= [
        //         ['image_url' => 'https://media-img-proxy.shopback.sg/OzbxUoodK88/czM6Ly9tZWRpYS1zZXJ2aWNlLXNiLXByb2Qtc2cvMTJjMTJjZGUtMzYzMy00NzU2LWI1YzgtNjNhZmY3MzI3MGM1LUhlcm8gTW9iaWxlX1NDQiBTdXBlciBTdW5kYXkgKDEpLnBuZw.png', 'tracking_url' => 'https://app.azworl.network'],
        //         ['image_url' => 'https://media-img-proxy.shopback.sg/D-liRJ0eYvE/czM6Ly9tZWRpYS1zZXJ2aWNlLXNiLXByb2Qtc2cvZjU1MTljODQtNGYwMy00NzFhLWFhN2EtNjAyZDg3YjVjYjM4LUhlcm8gTW9iaWxlIC0gTkVXIFRUICgxKS5wbmc.png', 'tracking_url' => 'https://app.azworl.network'],
        //         ['image_url' => 'https://media-img-proxy.shopback.sg/MhNy8KFQ_og/czM6Ly9tZWRpYS1zZXJ2aWNlLXNiLXByb2Qtc2cvNjMyYTVhOGQtNmYxOC00YzI2LWJlZTItMWZmNWUxN2VjNTc4LUhlcm8gTW9iaWxlX1NDQiBDYXJkIEFjcXVpc2l0aW9uLnBuZw.png', 'tracking_url' => 'https://app.azworl.network']
        //      ];
        
             $slider = Slider::where('cate', $type)->get();
            
             $data['items'] = $slider;     
            return $this->responseOK($data, 'success');
        } else if($type == 'global_top_deal') {

            $top_deals = Slider::where('cate', $type)->get();
        //    $data['items']= [
        //         ['image_url' => 'https://media-img-proxy.shopback.sg/1b43uBhACWA/czM6Ly9tZWRpYS1zZXJ2aWNlLXNiLXByb2Qtc2cvNGQwYWU3MmMtZGRjMS00YjFmLWE2NmUtMWIwMWE3OWFlNTljLU1pY3Jvc29mdCAgQnJhbmQgQmFubmVyIOKAkyA5LnBuZw.png', 'tracking_url' => 'https://app.azworl.network'],
        //         ['image_url' => 'https://media-img-proxy.shopback.sg/s86CgdJZ01E/czM6Ly9tZWRpYS1zZXJ2aWNlLXNiLXByb2Qtc2cvYjIyZTVkM2ItYzQ5ZS00NjA1LWJiMzctMWRhNDUwMjYxNDE1LWlIZXJiIEJyYW5kIEJhbm5lciAoMSkucG5n.png', 'tracking_url' => 'https://app.azworl.network'],
        //         ['image_url' => 'https://media-img-proxy.shopback.sg/JnY-X4aVoHM/czM6Ly9tZWRpYS1zZXJ2aWNlLXNiLXByb2Qtc2cvN2Q4NDM2ZGQtZTA4YS00Y2RjLTgxYWEtYmRlODc0MDEyMzBkLUJyYW5kIEJhbm5lciAtQXN1cy5wbmc.png', 'tracking_url' => 'https://app.azworl.network'],
        //         ['image_url' => 'https://media-img-proxy.shopback.sg/JnY-X4aVoHM/czM6Ly9tZWRpYS1zZXJ2aWNlLXNiLXByb2Qtc2cvN2Q4NDM2ZGQtZTA4YS00Y2RjLTgxYWEtYmRlODc0MDEyMzBkLUJyYW5kIEJhbm5lciAtQXN1cy5wbmc.png', 'tracking_url' => 'https://app.azworl.network'],
        //         ['image_url' => 'https://media-img-proxy.shopback.sg/JnY-X4aVoHM/czM6Ly9tZWRpYS1zZXJ2aWNlLXNiLXByb2Qtc2cvN2Q4NDM2ZGQtZTA4YS00Y2RjLTgxYWEtYmRlODc0MDEyMzBkLUJyYW5kIEJhbm5lciAtQXN1cy5wbmc.png', 'tracking_url' => 'https://app.azworl.network'],
        //         ['image_url' => 'https://media-img-proxy.shopback.sg/JnY-X4aVoHM/czM6Ly9tZWRpYS1zZXJ2aWNlLXNiLXByb2Qtc2cvN2Q4NDM2ZGQtZTA4YS00Y2RjLTgxYWEtYmRlODc0MDEyMzBkLUJyYW5kIEJhbm5lciAtQXN1cy5wbmc.png', 'tracking_url' => 'https://app.azworl.network'],
        //         ['image_url' => 'https://media-img-proxy.shopback.sg/JnY-X4aVoHM/czM6Ly9tZWRpYS1zZXJ2aWNlLXNiLXByb2Qtc2cvN2Q4NDM2ZGQtZTA4YS00Y2RjLTgxYWEtYmRlODc0MDEyMzBkLUJyYW5kIEJhbm5lciAtQXN1cy5wbmc.png', 'tracking_url' => 'https://app.azworl.network'],
        //         ['image_url' => 'https://media-img-proxy.shopback.sg/JnY-X4aVoHM/czM6Ly9tZWRpYS1zZXJ2aWNlLXNiLXByb2Qtc2cvN2Q4NDM2ZGQtZTA4YS00Y2RjLTgxYWEtYmRlODc0MDEyMzBkLUJyYW5kIEJhbm5lciAtQXN1cy5wbmc.png', 'tracking_url' => 'https://app.azworl.network'],
        //         ['image_url' => 'https://media-img-proxy.shopback.sg/JnY-X4aVoHM/czM6Ly9tZWRpYS1zZXJ2aWNlLXNiLXByb2Qtc2cvN2Q4NDM2ZGQtZTA4YS00Y2RjLTgxYWEtYmRlODc0MDEyMzBkLUJyYW5kIEJhbm5lciAtQXN1cy5wbmc.png', 'tracking_url' => 'https://app.azworl.network'],
        //      ];
            $data['items'] = $top_deals;
            return $this->responseOK($data, 'success');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
