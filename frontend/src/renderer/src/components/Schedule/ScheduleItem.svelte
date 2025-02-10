<script lang="ts">
  import type { ScheduleItemModel } from "../../models/scheduleItem.model";
  export let item: ScheduleItemModel;

  /**
   * Converts a UNIX timestamp to a time string in the format "HH:MM"
   * @param {number} UNIX_timestamp - The UNIX timestamp to convert (in seconds)
   * @returns {string} The formatted time string
   * @example
   * // returns "14:30"
   * timeConverter(1622548200)
   */
  function timeConverter(UNIX_timestamp: number) {
    const a = new Date(UNIX_timestamp * 1000);
    const hour = a.getHours();
    const min = a.getMinutes();
    const time = hour + ":" + min;
    return time;
  }
</script>

<div class="flex p-4 rounded-lg flex-row items-center gap-5 bg-secondary-700">
  <!-- hour indication -->
  {#if item.lessonNumberStart != ""}
    {#if item.lessonNumberStart === item.lessonNumberEnd}
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        {item.lessonNumberStart}
      </span>
    {:else}
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        {item.lessonNumberStart} - {item.lessonNumberEnd}
      </span>
    {/if}
  {:else}
    <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
      {timeConverter(item.start)}
      -
      {timeConverter(item.end)}
    </span>
  {/if}

  <!-- details -->
  <div>
    {#if item.subjectsFriendlyNames.length > 0}
      <p class="font-bold">{item.subjectsFriendlyNames.join(", ")}</p>
    {:else}
      <p class="font-bold">{item.subjects}</p>
    {/if}
    <p>{item.locations.join(", ")} - {item.teachers.join(", ")}</p>
  </div>
</div>
