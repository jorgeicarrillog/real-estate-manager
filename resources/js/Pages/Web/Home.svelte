<script>
    import Map from "@anoram/leaflet-svelte";
    import { InertiaLink, page } from '@inertiajs/inertia-svelte';
    import axios from 'axios'

    import Helmet from '@/Shared/Helmet.svelte';
    import LayoutWeb from '@/Shared/LayoutWeb.svelte';
    import MoneyFormat from '@/Shared/MoneyFormat.svelte';

    const route = window.route;

    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    /**
     * Next we will register the CSRF Token as a common header with Axios so that
     * all outgoing HTTP requests automatically have it attached. This is just
     * a simple convenience so we don't have to attach every token manually.
     */

    let token = document.head.querySelector('meta[name="csrf-token"]');

    if (token) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }

    let organizations;
    let properties;

    $: properties = $page.properties;
    $: organizations = $page.organizations;

    //map
    let options = {
        zoom: 14,
        center:[5.0504997,-75.4944],
        mapID: "map",
    };
    let MAP_EL;
    async function init() {
        let map = MAP_EL.getMap();
        
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                let latitude = position.coords.latitude;
                let longitude = position.coords.longitude;
                map.setView([latitude,longitude],13);
                setMarkers(latitude, longitude);
            }, ()=>{
                setMarkers(5.0504997, -75.4944);
            }, {timeout:3000});
        }
    }

    function setMarkers(latitude, longitude){
        axios.post(route('web-api.geo-properties'), 
        {
            latitude:latitude,
            longitude:longitude,
        }).then((resp)=>{
            let markers = resp.data;
            MAP_EL.updateMarkers({
                markers: markers,
            });
        });
    }

</script>

<Helmet title="Inmobiliarias" />

<LayoutWeb>
    <section class="text-gray-600 body-font relative">
        <div class="absolute inset-0 bg-gray-300" style="height: 100%;z-index: 1;filter: grayscale(1) contrast(1.2) opacity(0.4);">
            <Map {options} bind:this={MAP_EL} on:ready={init} />
        </div>
        <div class="container px-5 py-24 mx-auto flex">
          <div class="lg:w-1/3 md:w-1/2 bg-white rounded-lg p-8 flex flex-col md:ml-auto w-full mt-10 md:mt-0 relative z-10 shadow-md">
            <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">Feedback</h2>
            <p class="leading-relaxed mb-5 text-gray-600">Post-ironic portland shabby chic echo park, banjo fashion axe</p>
            <div class="relative mb-4">
              <label for="email" class="leading-7 text-sm text-gray-600">Email</label>
              <input type="email" id="email" name="email" class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
            </div>
            <div class="relative mb-4">
              <label for="message" class="leading-7 text-sm text-gray-600">Message</label>
              <textarea id="message" name="message" class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
            </div>
            <button class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">Button</button>
            <p class="text-xs text-gray-500 mt-3">Chicharrones blog helvetica normcore iceland tousled brook viral artisan.</p>
          </div>
        </div>
    </section>
    <section class="text-gray-600 body-font">
        <div class="container px-5 mx-auto">
          <div class="flex flex-col">
            <div class="h-1 bg-gray-200 rounded overflow-hidden">
              <div class="w-24 h-full bg-indigo-500"></div>
            </div>
            <div class="flex flex-wrap sm:flex-row flex-col py-6">
              <h1 class="sm:w-2/5 text-gray-900 font-medium title-font text-2xl mb-2 sm:mb-0">Propiedades</h1>
              <p class="sm:w-3/5 leading-relaxed text-base sm:pl-10 pl-0">Últimas propiedades que te podrian interesar.</p>
            </div>
        </div>
        <div class="container px-3 py-12 mx-auto">
          <div class="flex flex-wrap -m-4">
            {#each properties as pr (pr.id)}
            <div class="lg:w-1/4 md:w-1/2 p-4 w-full">
              <a class="block relative h-48 rounded overflow-hidden" href="/">
                <img alt="ecommerce" class="object-cover object-center w-full h-full block" src="{(pr.photo)?pr.photo:'https://dummyimage.com/420x260'}">
              </a>
              <div class="mt-4">
                <h3 class="text-gray-500 text-xs tracking-widest title-font mb-1">{pr.properties_type.name}</h3>
                <h2 class="text-gray-900 title-font text-lg font-medium">{pr.title}</h2>
                <p class="mt-1">{pr.citie.name}, {pr.citie.department.name}</p>
                <p class="mt-1">$<MoneyFormat value={pr.value}></MoneyFormat></p>
              </div>
            </div>
            {/each}
          </div>
        </div>
    </section>
    <section class="text-gray-600 body-font">
        <div class="container px-5 py-24 mx-auto">
        <div class="flex flex-col text-center w-full mb-20">
            <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">Algunas inmobiliarias registradas</h1>
            <p class="lg:w-2/3 mx-auto leading-relaxed text-base">Publicaciones de inmobiliarias de todas las ciudades para que sea facil encontrar la propiedad de tus sueños.</p>
        </div>
        <div class="flex flex-wrap -m-2">
            {#each organizations as org (org.id)}
            <div class="p-2 lg:w-1/4 md:w-1/3 sm:w-1/2 w-full">
                <div class="h-full flex items-center border-gray-200 border p-4 rounded-lg">
                <img alt="team" class="w-16 h-16 bg-gray-100 object-cover object-center flex-shrink-0 rounded-full mr-4" src="{(org.photo)?org.photo:'https://dummyimage.com/80x80'}">
                <div class="flex-grow">
                    <h2 class="text-gray-900 title-font font-medium">{org.name}</h2>
                    <p class="text-gray-500"><small><b>Teléfono:</b> {org.phone}</small></p>
                    <p class="text-gray-500"><small>{org.citie.name}, {org.citie.department.name}</small></p>
                </div>
                </div>
            </div>
            {/each}
        </div>
        </div>
    </section>
</LayoutWeb>