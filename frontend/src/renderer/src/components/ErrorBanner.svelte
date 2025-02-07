<script lang="ts">
    import { Banner } from 'flowbite-svelte';
    import { CloseCircleOutline } from 'flowbite-svelte-icons';
    import { fade } from 'svelte/transition';

    export let message = '';

    $: {
        if (message) {
            setTimeout(() => {
                message = '';
            }, 5000);
        }
    }
</script>

{#if message}
  <div transition:fade>
    <Banner position="absolute" color="red">
      <p class="flex items-center text-sm font-medium text-red-800 dark:text-red-200">
        <span class="inline-flex p-1 me-3 bg-red-100 rounded-full dark:bg-red-900">
          <CloseCircleOutline class="w-3 h-3 text-red-500 dark:text-red-300" />
          <span class="sr-only">Error</span>
        </span>
        <span>
          {message}
      </p>
    </Banner>
  </div>
{/if}