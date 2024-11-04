<script lang="ts">
  import ScheduleItem from "./ScheduleItem.svelte";
  import { Button } from "flowbite-svelte";
  import { ArrowLeftOutline, ArrowRightOutline } from 'flowbite-svelte-icons';
  import { getCurrentDate } from "../../services/TimeService";

  $: currentDate = getCurrentDate();
</script>

<!-- Date navigation -->
<div class="flex justify-center my-5 w-full">
<div class="flex justify-between my-5 w-3/6">

  <!-- Previous -->
  <Button class="p-2 w-10">
    <ArrowLeftOutline />
  </Button>

  <!-- Current date -->
  <h2 class="text-xl font-bold text-center w-full">{currentDate}</h2>
  
  <!-- Next -->
  <Button class="p-2 w-10">
    <ArrowRightOutline />
  </Button>
</div>
</div>

<ScheduleItem />