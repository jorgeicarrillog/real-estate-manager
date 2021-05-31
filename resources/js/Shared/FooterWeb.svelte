<script>
    import Logo from '@/Shared/Logo.svelte';
    import { InertiaLink } from '@inertiajs/inertia-svelte';
    import moment from 'moment';
</script>

<footer class="text-gray-600 body-font">
    <div class="container px-5 py-8 mx-auto flex items-center sm:flex-row flex-col">
        <InertiaLink class="flex title-font font-medium items-center md:justify-start justify-center text-gray-900" href="/">
            <Logo class="h-10 text-white p-2 bg-indigo-500 rounded-full" viewBox="0 0 120 28" width="120" height="28"/>
        </InertiaLink>
      <p class="text-sm text-gray-500 sm:ml-4 sm:pl-4 sm:border-l-2 sm:border-gray-200 sm:py-2 sm:mt-0 mt-4">© {moment().format('Y')} Inmo CRM —
        <a href="https://github.com/jorgeicarrillog" class="text-gray-600 ml-1" rel="noopener noreferrer" target="_blank">@jorgeicarrillog</a>
      </p>
      <span class="inline-flex sm:ml-auto sm:mt-0 mt-4 justify-center sm:justify-start">
        <a class="text-gray-500" href="https://github.com/jorgeicarrillog/real-estate-manager">
          <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-10 h-10" viewBox="0 0 24 24">
            <path xmlns="http://www.w3.org/2000/svg" d="M 14.09375 7.152344 C 14.09375 3.699219 11.140625 0.902344 7.5 0.902344 C 3.859375 0.902344 0.90625 3.699219 0.90625 7.152344 C 0.90625 9.890625 2.765625 12.222656 5.355469 13.066406 C 5.511719 12.957031 5.695312 12.824219 5.695312 12.597656 C 5.695312 12.253906 5.6875 11.371094 5.6875 11.371094 C 5.6875 11.371094 5.398438 11.417969 4.992188 11.417969 C 3.960938 11.417969 3.636719 10.796875 3.480469 10.453125 C 3.28125 10 3.019531 9.789062 2.742188 9.617188 C 2.570312 9.515625 2.53125 9.390625 2.726562 9.355469 C 3.640625 9.195312 3.871094 10.332031 4.480469 10.511719 C 4.914062 10.640625 5.472656 10.585938 5.75 10.414062 C 5.789062 10.070312 6.054688 9.769531 6.273438 9.609375 C 4.726562 9.46875 3.808594 8.964844 3.332031 8.148438 L 3.28125 8.058594 L 3.164062 7.800781 L 3.125 7.707031 C 2.976562 7.292969 2.910156 6.820312 2.910156 6.289062 C 2.910156 5.351562 3.222656 4.996094 3.640625 4.496094 C 3.320312 3.425781 3.753906 2.695312 3.753906 2.695312 C 3.753906 2.695312 4.425781 2.5625 5.691406 3.417969 C 6.375 3.140625 8.207031 3.117188 9.074219 3.355469 C 9.605469 3.027344 10.574219 2.554688 10.96875 2.6875 C 11.074219 2.847656 11.304688 3.320312 11.105469 4.351562 C 11.238281 4.578125 11.933594 5.058594 11.933594 6.421875 C 11.925781 6.925781 11.867188 7.351562 11.765625 7.707031 L 11.707031 7.890625 C 11.707031 7.890625 11.675781 7.980469 11.640625 8.0625 L 11.597656 8.152344 C 11.140625 9.105469 10.195312 9.457031 8.675781 9.605469 C 9.167969 9.898438 9.308594 10.265625 9.308594 11.261719 C 9.308594 12.253906 9.296875 12.386719 9.300781 12.617188 C 9.300781 12.816406 9.472656 12.960938 9.625 13.074219 C 12.226562 12.234375 14.09375 9.898438 14.09375 7.152344 Z M 14.09375 7.152344 "/>
          </svg>
        </a>
      </span>
    </div>
  </footer>