<script lang="ts">
  import { Alert } from "flowbite-svelte";
  import { ChevronDownOutline, CloseCircleSolid } from "flowbite-svelte-icons";
  import { fade } from "svelte/transition";
  import type { ErrorModel } from "../models/error.model";

  export let error: ErrorModel | null = null;
  export let size: "sm" | "md" = "md";
  let showDetails = false;
</script>

{#if error}
  <div transition:fade>
    <Alert border color="red" class={size === "sm" ? "p-2" : ""}>
      <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-2 align-center">
          <CloseCircleSolid
            slot="icon"
            class={size === "sm" ? "w-4 h-4" : "w-5 h-5"}
          />
          <span class="font-medium">{error.message}</span>
        </div>
        {#if error.details}
          <button type="button" on:click={() => (showDetails = !showDetails)}>
            <ChevronDownOutline
              class="transition-transform {size === 'sm'
                ? 'w-2 h-2'
                : 'w-3 h-3'} {showDetails ? 'rotate-180' : ''}"
            />
          </button>
        {/if}
      </div>
      {#if showDetails && error.details}
        <div class="mt-1 {size === 'sm' ? 'ml-6' : 'ml-7'}" transition:fade>
          <p class="text-xs">
            {error.details}
          </p>
        </div>
      {/if}
    </Alert>
  </div>
{/if}
