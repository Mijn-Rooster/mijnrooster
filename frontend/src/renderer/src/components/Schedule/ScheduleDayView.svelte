<script lang="ts">
  import ScheduleItem from "./ScheduleItem.svelte";
  import { Button } from "flowbite-svelte";
  import { ArrowLeftOutline, ArrowRightOutline } from 'flowbite-svelte-icons';

</script>

<!-- Date navigation -->
<div class="flex justify-between">
  <!-- Previous -->
  <Button class="p-2 w-10">
    <ArrowLeftOutline />
  </Button>
  <!-- Current date -->
  <p>Maandag 12 oktober 2021</p>
  <!-- Next -->
  <Button class="p-2 w-10">
    <ArrowRightOutline />
  </Button>
</div>

<ScheduleItem />