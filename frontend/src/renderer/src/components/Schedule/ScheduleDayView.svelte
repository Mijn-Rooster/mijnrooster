<script lang="ts">
  import ScheduleItem from "./ScheduleItem.svelte";
  import { Button } from "flowbite-svelte";
  import { ArrowLeftOutline, ArrowRightOutline } from 'flowbite-svelte-icons';
  import { getCurrentDate } from "../../services/TimeService";

  $: currentDate = getCurrentDate();
</script>

<!-- Date navigation -->
<div class="flex justify-between">
  <!-- Previous -->
  <Button class="p-2 w-10">
    <ArrowLeftOutline />
  </Button>
  <!-- Current date -->
  <p>{currentDate}</p>
  <!-- Next -->
  <Button class="p-2 w-10">
    <ArrowRightOutline />
  </Button>
</div>

<ScheduleItem />