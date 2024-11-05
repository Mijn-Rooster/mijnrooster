<script lang="ts">
  import ScheduleItem from "./ScheduleItem.svelte";
  import { ButtonGroup, Button } from 'flowbite-svelte';
  import { ArrowLeftOutline, ArrowRightOutline } from 'flowbite-svelte-icons';
  import { getCurrentDate } from "../../services/TimeService";
  import { navigate } from "../../stores/RouterStore";

  $: currentDate = getCurrentDate();
</script>

<!-- Date navigation -->
<div class="flex justify-center my-5 w-full">


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


<ScheduleItem />