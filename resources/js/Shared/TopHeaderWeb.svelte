<script>
  import { page } from '@inertiajs/inertia-svelte';
  import {InertiaLink} from '@inertiajs/inertia-svelte';
  import MainMenuWeb from '@/Shared/MainMenuWeb.svelte';
  import Logo from '@/Shared/Logo.svelte';
  import Icon from '@/Shared/Icon.svelte';

  const route = window.route;
  let { auth } = $page;
  $: auth = $page.auth;
</script>
<header class="text-gray-600 body-font">
  <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
    <InertiaLink class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0" href="/">
      <Logo class="h-10 text-white p-2 bg-indigo-500 rounded-full" viewBox="0 0 120 28" width="120" height="28"/>
    </InertiaLink>
    <MainMenuWeb className="md:ml-auto flex flex-wrap items-center text-base justify-center mr-3"/>
    {#if auth.user}
    <a href="{route('dashboard')}" class="inline-flex items-center bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base mt-4 md:mt-0">
      <Icon name="dashboard" class="w-4 h-4 ml-1" viewBox="0 0 24 24" />
      Dashboard
    </a>
    {:else}
    <a href="{route('login')}" class="inline-flex items-center bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base mt-4 md:mt-0">
      Ingresar
      <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-1" viewBox="0 0 24 24">
        <path d="M5 12h14M12 5l7 7-7 7"></path>
      </svg>
    </a>
    {/if}
  </div>
</header>