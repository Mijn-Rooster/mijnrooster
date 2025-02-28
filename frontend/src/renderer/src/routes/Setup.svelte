<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
  import FirstStep from "../components/Setup/FirstStep.svelte";
  import SecondStep from "../components/Setup/SecondStep.svelte";
  import { route } from "../stores/router.store";

  let setupStep: number;

  // Assign setupStep from route params
  $: setupStep = Number($route.params.setupStep) || 0;
</script>

<MenuBar />

<div class="pt-8">
  {#if setupStep === 0}
    <FirstStep />
  {:else if setupStep === 1}
    <SecondStep />
  {/if}
</div>
