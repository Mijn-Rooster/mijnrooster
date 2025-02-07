<script lang="ts">
    import { CloseCircleOutline } from 'flowbite-svelte-icons';
    import { fade } from 'svelte/transition';

    export let message = '';
</script>

{#if message}
  <div transition:fade class="p-4 bg-red-50 border border-red-200 rounded-lg shadow-sm dark:bg-red-900/10 dark:border-red-800">
      <p class="flex items-center text-sm font-medium text-red-800 dark:text-red-200">
        <span class="inline-flex p-1 me-3 bg-red-100 rounded-full dark:bg-red-900">
          <CloseCircleOutline class="w-3 h-3 text-red-500 dark:text-red-300" />
          <span class="sr-only">Error</span>
        </span>
        <span>
          {message}
        </span>
      </p>
  </div>
{/if}