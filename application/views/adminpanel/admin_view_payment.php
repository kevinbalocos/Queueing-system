<?php
$left_items = array_slice($payment, 0, 20);
$right_items = array_slice($payment, 20);
$grid_cols = count($left_items) < 5 ? count($left_items) : 5;

$currentUser = isset($_SESSION['username']) ? strval($_SESSION['username']) : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Payment Queue</title>
  <link rel="stylesheet"
    href="<?php echo base_url('assets/css/user_view_landtax.css?v=' . filemtime('assets/css/user_view_landtax.css')); ?>">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-gray-100">

<div class="flex h-auto">
    <div class="flex-1 bg-white p-5 h-[calc(100vh-50px)] rounded-lg shadow-md overflow-y-auto">
      <div id="queue-container" class="flex flex-wrap gap-1 max-h-[calc(100vh-100px)] bg-cyan-100 overflow-y-auto">
        <?php if ($payment): ?>
          <?php foreach ($left_items as $item): ?>
            <?php
            $isProcessingByCurrentUser = ($item->processing_by && strval($item->processing_by) === $currentUser);
            ?>
            <div class="flex flex-col flex-1 min-w-96 queue-item p-3 bg-white border rounded-lg m-1"
              id="queue-item-<?= $item->id; ?>" data-id="<?= $item->id; ?>">
              <!-- Main Content Container (Queue Details) -->
              <div class="flex flex-col flex-grow space-y-4 max-w-full">
                <div class="flex justify-between">
                  <p class="text-gray-800 text-[calc(.8vw)] font-semibold uppercase"><?= $item->reason; ?></p>
                  <p class="text-gray-500 text-[calc(.8vw)]">
                    <?= date('M d Y, h:i A', strtotime($item->created_at)); ?>
                  </p>
                </div>
                <h3 class="flex flex-col justify-center items-center flex-grow">
                  <span class="font-semibold text-[calc(6vw)] leading-none"><?= $item->queue_number; ?></span>
                  <span class="text-gray-600 uppercase text-[calc(.7vw)]"><?= $item->name; ?></span>
                </h3>
              </div>

              <!-- Status Container -->
              <p class="text-gray-500 text-[calc(.7vw)] text-right pb-10">
                <span class="status-text bg-cyan-100 text-cyan-500 rounded-full px-3 py-1 font-semibold">
                  <?= $item->processing_by ? "Processing by {$item->processing_by}" : "Payment"; ?>
                </span>
              </p>

              <!-- Button Container -->
              <div class="flex justify-between items-center mt-auto pt-4 border-t">
                <div class="flex space-x-2">
                  <button
                    class="proceed-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg <?= $isProcessingByCurrentUser ? '' : 'opacity-50 cursor-not-allowed' ?>"
                    data-id="<?= $item->id; ?>"
                    data-url="<?= base_url("controller_queueing/proceed_to_fireprotection/{$item->id}") ?>"
                    <?= $isProcessingByCurrentUser ? '' : 'disabled'; ?>>
                    <i class="fa-solid fa-circle-check text-[calc(1.2vw)]"></i>
                  </button>
                  <button
                    class="processing-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg <?= $item->processing_by ? 'opacity-50 cursor-not-allowed' : '' ?>"
                    data-id="<?= $item->id; ?>" <?= $item->processing_by ? 'disabled' : ''; ?>>
                    <i class="fa-solid fa-clock text-[calc(1.2vw)]"></i>
                  </button>
                </div>
                <div>
                  <button
                    class="delete-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg"
                    data-id="<?= $item->id; ?>" data-url="<?= base_url("controller_queueing/delete_queue/{$item->id}") ?>">
                    <i class="fa-solid fa-trash-can text-[calc(1.2vw)]"></i>
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Right Section (Queue List & Add Form) -->
    <div class="ml-5 bg-white p-5 w-[400px]  h-[calc(100vh-50px)] rounded-lg shadow-md flex flex-col">
      <h2 class="text-2xl font-bold text-cyan-900 uppercase tracking-widest text-center">Payment Queue List</h2>
      <ul id="queueList" class="mt-3 overflow-auto h-[1000px] space-y-3 p-2 bg-white rounded-lg shadow-md border">
        <?php foreach ($right_items as $item): ?>
          <li
            class="p-5 bg-cyan-50 border border-cyan-400 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 hover:bg-cyan-50 flex flex-col space-y-4"
            data-queue_id="<?= $item->id; ?>" data-queue_number="<?= $item->queue_number; ?>"
            data-name="<?= $item->name; ?>" data-reason="<?= $item->reason; ?>"
            data-created_at="<?= $item->created_at; ?>"
            data-proceed_url="<?= base_url("controller_queueing/proceed_to_fireprotection/{$item->id}") ?>"
            data-processing_by="<?= $item->processing_by ? $item->processing_by : '' ?>">

            <!-- Queue Header -->
            <div class="flex items-center gap-4">
              <div
                class="w-12 h-12 flex items-center justify-center text-white font-bold text-xl bg-cyan-600 rounded-full shadow-md">
                <?= $item->queue_number; ?>
              </div>
              <div class="flex flex-col">
                <div class="text-lg font-semibold text-cyan-900">
                  <?= $item->name; ?>
                </div>
                <span class="text-xs text-gray-500">
                  <?= date('M d, Y, h:i A', strtotime($item->created_at)); ?>
                </span>
              </div>
            </div>

            <!-- Queue Reason Badge -->
            <div class="mt-3 flex justify-end">
              <span class="text-sm px-4 py-2 rounded-full bg-cyan-100 text-cyan-800 font-medium shadow-sm">
                <?= $item->reason; ?>
              </span>
            </div>

          </li>
        <?php endforeach; ?>
      </ul>

      <!-- Add to Payment Queue Form -->
      <form action="<?= base_url('controller_queueing/add_to_payment'); ?>" method="post" class="mt-5">
        <input type="text" name="name" placeholder="Enter Name" required class="border p-2 w-full rounded" />
        <select name="reason" required class="border p-2 w-full rounded mt-2 text-cyan-800 font-semibold tracking-wide">
          <option class="text-cyan-800 font-semibold tracking-wide" value="Visa Payment">Visa Payment</option>
          <option class="text-cyan-800 font-semibold tracking-wide" value="Tax Payment">Tax Payment</option>
        </select>
        <button type="submit"
          class="mt-2 w-full bg-cyan-500 uppercase tracking-wider font-semibold text-white py-2 rounded">
          Add to Payment Queue
        </button>
      </form>
    </div>
  </div>


  <script>
    const socket = new WebSocket("ws://localhost:8080");

    socket.onopen = () => console.log("Connected to WebSocket server (Payment)");

    socket.onmessage = (event) => {
      try {
        const data = JSON.parse(event.data);

        if (data.action === "proceed_to_payment") {
          console.log("New Payment queue item received:", data);
          addNewPaymentQueue(data);
        } else if (data.action === "proceed_to_fireprotection") {
          console.log("Queue item moved to Fire Protection:", data);
          removeQueueItem(data.queue_id);
        } else if (data.action === "delete_queue") {
          console.log(`Queue ID ${data.queue_id} deleted.`);
          removeQueueItem(data.queue_id);
        }
      } catch (error) {
        console.error("Error parsing WebSocket data:", error);
      }
    };

    socket.onerror = (error) => console.error("WebSocket Error: ", error);
    socket.onclose = () => console.log("Disconnected from WebSocket server");

    function formatTimestamp(dateString) {
      const date = new Date(dateString);
      return isNaN(date.getTime())
        ? "Invalid Date"
        : date.toLocaleString("en-US", {
          month: "short",
          day: "2-digit",
          year: "numeric",
          hour: "2-digit",
          minute: "2-digit",
          hour12: true,
        });
    }

    function createQueueItem(data, defaultStatus) {
      const formattedCreatedAt = new Date(data.created_at).toLocaleString('en-US', {
        month: 'short', day: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit', hour12: true
      });

      const proceedUrl = `http://localhost/OJT/queueing-system/index.php/controller_queueing/proceed_to_fireprotection/${data.queue_id}`;
      const deleteUrl = `http://localhost/OJT/queueing-system/index.php/controller_queueing/delete_queue/${data.queue_id}`;

      const isProcessingByCurrentUser = data.processing_by && String(data.processing_by) === String(defaultStatus);
      const isProcessing = !!data.processing_by;
      const processingText = isProcessing ? `Processing by ${data.processing_by}` : "Payment";
      const statusColorClass = "bg-cyan-100 text-cyan-500";
      const processingClass = isProcessing ? "opacity-50 cursor-not-allowed" : "";

      return `
        <!-- Queue Item -->
        <div id="queue-item-${data.queue_id}" data-id="${data.queue_id}" 
            class="flex flex-col flex-1 min-w-96 queue-item p-3 bg-white border rounded-lg m-1 shadow-md">
            
            <!-- Header: Reason & Timestamp -->
            <div class="flex justify-between">
                <p class="text-gray-800 text-[calc(.8vw)] font-semibold uppercase">${data.reason}</p>
                <p class="text-gray-500 text-[calc(.8vw)]">${formattedCreatedAt}</p>
            </div>

            <!-- Queue Number & Name -->
            <h3 class="flex flex-col justify-center items-center flex-grow">
                <span class="font-semibold text-[calc(6vw)] leading-none">${data.queue_number}</span>
                <span class="text-gray-600 uppercase text-[calc(.7vw)]">${data.name}</span>
            </h3>

            <!-- Status -->
            <p class="text-gray-500 text-[calc(.7vw)] text-right pb-10">
                <span class="status-text ${statusColorClass} rounded-full px-3 py-1 font-semibold">
                    ${processingText}
                </span>
            </p>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center mt-auto pt-4 border-t">
                <div class="flex space-x-2">
                    <button class="proceed-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg 
                            ${isProcessingByCurrentUser ? '' : 'opacity-50 cursor-not-allowed'}"
                            data-id="${data.queue_id}" data-url="${proceedUrl}" ${isProcessingByCurrentUser ? '' : 'disabled'}>
                        <i class="fa-solid fa-circle-check text-[calc(1.2vw)]"></i>
                    </button>

                    <button class="processing-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg 
                            ${processingClass}" data-id="${data.queue_id}" ${isProcessing ? 'disabled' : ''}>
                        <i class="fa-solid fa-clock text-[calc(1.2vw)]"></i>
                    </button>
                </div>

                <button class="delete-btn bg-white py-3 px-3 text-cyan-900 hover:bg-gray-50 rounded-full border border-cyan-50 shadow-lg"
                        data-id="${data.queue_id}" data-url="${deleteUrl}">
                    <i class="fa-solid fa-trash-can text-[calc(1.2vw)]"></i>
                </button>
            </div>
        </div>
    `;
    }

    function addNewPaymentQueue(data) {
      const queueContainer = document.getElementById("queue-container");
      const queueList = document.getElementById("queueList");

      if (!queueContainer) {
        console.error("Error: Queue container not found.");
        return;
      }
      if (!queueList) {
        console.error("Error: Queue list not found.");
        return;
      }

      const queueId = `queue-item-${data.queue_id}`;
      if (document.getElementById(queueId)) {
        console.warn(`Queue item ${queueId} already exists.`);
        return;
      }

      const listItem = document.createElement("div");

      if (queueContainer.children.length < 20) {
        listItem.innerHTML = createQueueItem(data, "Payment");
        queueContainer.appendChild(listItem.firstElementChild);
      } else {
        listItem.className = "p-5 bg-cyan-50 border border-cyan-400 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 hover:bg-cyan-50 flex flex-col space-y-4";
        listItem.innerHTML = `
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 flex items-center justify-center text-white font-bold text-xl bg-cyan-600 rounded-full shadow-md">
                    ${data.queue_number}
                </div>
                <div class="flex flex-col">
                    <div class="text-lg font-semibold text-cyan-900">
                        ${data.name}
                    </div>
                    <span class="text-xs text-gray-500">
                        ${new Date(data.created_at).toLocaleString('en-US', {
          month: 'short', day: '2-digit', year: 'numeric',
          hour: '2-digit', minute: '2-digit', hour12: true
        })}
                    </span>
                </div>
            </div>
            <div class="mt-3 flex justify-end">
                <span class="text-sm px-4 py-2 rounded-full bg-cyan-100 text-cyan-800 font-medium shadow-sm">
                    ${data.reason}
                </span>
            </div>
        `;

        listItem.setAttribute("data-queue_id", data.queue_id);
        listItem.setAttribute("data-queue_number", data.queue_number);
        listItem.setAttribute("data-name", data.name);
        listItem.setAttribute("data-reason", data.reason);
        listItem.setAttribute("data-created_at", data.created_at);
        listItem.setAttribute("data-proceed_url", `http://localhost/OJT/queueing-system/index.php/controller_queueing/proceed_to_fireprotection/${data.queue_id}`);

        queueList.appendChild(listItem);
      }
      updateGridLayout();
    }

    function removeQueueItem(queueId) {
      let queueItem = document.getElementById(`queue-item-${queueId}`);

      if (queueItem) {
        queueItem.remove();
        console.log(`Queue ID ${queueId} removed from UI.`);

        moveFirstRightItemToLeft();
        updateGridLayout();
      } else {
        console.warn(`Queue item ${queueId} not found.`);
      }
    }

    function moveFirstRightItemToLeft() {
      const queueContainer = document.getElementById("queue-container");
      const queueList = document.getElementById("queueList");

      if (queueContainer.children.length < 20 && queueList.children.length > 0) {
        const firstRightItem = queueList.children[0];

        const queueData = {
          queue_id: firstRightItem.getAttribute("data-queue_id") || null,
          queue_number: firstRightItem.getAttribute("data-queue_number") || "N/A",
          name: firstRightItem.getAttribute("data-name") || "Unknown",
          reason: firstRightItem.getAttribute("data-reason") || "No reason provided",
          created_at: firstRightItem.getAttribute("data-created_at"),
          proceed_url: firstRightItem.getAttribute("data-proceed_url") ||
            `http://localhost/OJT/queueing-system/index.php/controller_queueing/proceed_to_fireprotection/${firstRightItem.getAttribute("data-queue_id")}`
        };

        if (!queueData.queue_id) {
          console.error("❌ Queue ID is undefined. Cannot move item.");
          return;
        }

        const tempDiv = document.createElement("div");
        tempDiv.innerHTML = createQueueItem(queueData, "Payment");

        const newQueueItem = tempDiv.firstElementChild;

        Object.keys(queueData).forEach(key => {
          newQueueItem.dataset[key] = queueData[key];
        });

        firstRightItem.remove();
        queueContainer.appendChild(newQueueItem);

        console.log(`✅ Moved queue item (ID: ${queueData.queue_id}) to the left preserving original timestamp: ${queueData.created_at}`);
      }
    }

    function updateGridLayout() {
      const queueContainer = document.getElementById("queue-container");
      queueContainer.style.gridTemplateColumns = `repeat(${Math.min(queueContainer.children.length, 5)}, 1fr)`;
    }
  </script>


  <script>
    document.querySelector('form').addEventListener('submit', function (e) {
      e.preventDefault(); // Prevent form submission

      let formData = new FormData(this); // Gather form data

      fetch("<?= base_url('controller_queueing/add_to_payment'); ?>", {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            // Show success toast
            Toastify({
              text: data.message,
              duration: 3000,
              close: true,
              gravity: "top",
              position: "right",
              backgroundColor: "linear-gradient(to right, #00b09b,rgba(15, 156, 185, 0.53))"
            }).showToast();
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Toastify({
            text: 'An error occurred. Please try again.',
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "linear-gradient(to right, #FF5F6D, #FFC371)"
          }).showToast();
        });
    });

  </script>
  <script>
    document.addEventListener('click', function (e) {
      if (e.target.closest('.proceed-btn')) {
        e.preventDefault();

        let button = e.target.closest('.proceed-btn');
        let url = button.getAttribute("data-url");
        let queueId = button.getAttribute("data-id");

        if (!url) {
          console.error("Error: Proceed URL is missing.");
          return;
        }

        button.disabled = true;
        button.classList.add("opacity-50", "cursor-not-allowed");

        fetch(url, { method: 'GET' })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              Toastify({
                text: data.message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right, #00b09b, #1165b7)"
              }).showToast();

              console.log(`✅ Proceeded queue item ${queueId}, removing immediately.`);

              removeQueueItem(queueId);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            Toastify({
              text: 'An error occurred. Please try again.',
              duration: 3000,
              close: true,
              gravity: "top",
              position: "right",
              backgroundColor: "linear-gradient(to right, #FF5F6D, #FFC371)"
            }).showToast();

            // Re-enable button on error
            button.disabled = false;
            button.classList.remove("opacity-50", "cursor-not-allowed");
          });
      }
    });
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const socket = new WebSocket("ws://localhost:8080");

      socket.onopen = function () {
        console.log("✅ WebSocket connection established (Payment)");
      };

      socket.onmessage = function (event) {
        try {
          const data = JSON.parse(event.data);
          console.log("📩 Received WebSocket Message:", data);

          if (data.action === "update_payment_queue") {
            console.log(`Queue ${data.queue_id} marked as processing by ${data.processing_by}`);
            updatePaymentQueueStatus(
              data.queue_id,
              `Processing by ${data.processing_by}`,
              "text-cyan-500",
              data.processing_by,
              data.created_at
            );
          }
        } catch (error) {
          console.error("❌ WebSocket JSON Error:", error);
        }
      };

      socket.onerror = function (error) {
        console.error("❌ WebSocket Error:", error);
      };

      socket.onclose = function () {
        console.log("⚠️ WebSocket connection closed (Payment)");
      };

      document.body.addEventListener("click", function (e) {
        const button = e.target.closest(".processing-btn");

        if (button) {
          e.preventDefault();
          const queueId = button.getAttribute("data-id");

          if (!queueId) {
            console.error("❌ Queue ID is undefined.");
            Swal.fire({ title: "Error!", text: "Invalid queue ID.", icon: "error", position: "top" });
            return;
          }

          fetch("<?= base_url('controller_queueing/mark_as_processing_payment'); ?>", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ queue_id: queueId }),
          })
            .then(response => response.json())
            .then(data => {
              if (data.status === "success") {
                console.log("✅ Payment queue marked as processing:", data);

                updatePaymentQueueStatus(queueId, `Processing by ${data.processing_by}`, "text-cyan-500", data.processing_by);

                socket.send(JSON.stringify({
                  action: "update_payment_queue",
                  queue_id: queueId,
                  status: "processing",
                  processing_by: data.processing_by
                }));

                Swal.close();
              } else {
                throw new Error(data.message);
              }
            })
            .catch(error => {
              console.error("❌ Fetch Error:", error);
              Swal.fire({ title: "Error!", text: error.message, icon: "error", position: "top" });
            });
        }
      });

      function updatePaymentQueueStatus(queueId, statusText, textColor, processingBy, createdAt) {
        let queueRow = document.querySelector(`#queue-item-${queueId}`);
        if (!queueRow) {
          console.warn(`⚠️ Payment queue row with ID ${queueId} not found!`);
          return;
        }

        let statusTextElement = queueRow.querySelector(".status-text");
        let proceedBtn = queueRow.querySelector(".proceed-btn");
        let processingBtn = queueRow.querySelector(".processing-btn");

        if (statusTextElement) {
          statusTextElement.innerText = statusText;
          statusTextElement.classList.remove("text-green-500", "text-yellow-500", "text-cyan-500", "text-red-500");
          statusTextElement.classList.add(textColor);
        }

        if (processingBtn) {
          processingBtn.classList.add("opacity-50", "cursor-not-allowed");
          processingBtn.setAttribute("disabled", "disabled");
        }

        if (processingBy && String(processingBy) === String(currentUser)) {
          console.log(`✅ User ${currentUser} is processing Queue ${queueId}, enabling proceed button.`);
          proceedBtn.classList.remove("opacity-50", "cursor-not-allowed");
          proceedBtn.removeAttribute("disabled");
        } else {
          console.log(`❌ Queue ${queueId} is being processed by another user (${processingBy}). Disabling proceed button.`);
          proceedBtn.classList.add("opacity-50", "cursor-not-allowed");
          proceedBtn.setAttribute("disabled", "disabled");
        }
      }

      let currentUser = "<?= $currentUser; ?>";
      console.log("📌 Current User (JavaScript):", currentUser);
    });
  </script>
  <script>
    document.addEventListener("click", function (e) {
      let deleteBtn = e.target.closest(".delete-btn");
      if (!deleteBtn) return;

      e.preventDefault();

      let queueItem = deleteBtn.closest(".queue-item"); // Find the parent queue item
      let queueId = deleteBtn.dataset.id;
      let url = deleteBtn.dataset.url;

      if (!url) {
        console.error("Error: No URL found for delete action.");
        return;
      }

      Swal.fire({
        title: "Are you sure?",
        text: "This will permanently delete this record.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel"
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(url, { method: "POST" }) // Ensure the request is handled correctly in CodeIgniter
            .then(response => response.text()) // First, get raw response
            .then(text => {
              console.log("Raw Response:", text); // Debugging

              let data;
              try {
                data = JSON.parse(text.trim()); // Ensure valid JSON
              } catch (error) {
                console.error("JSON Parse Error:", error, "Response Text:", text);
                Swal.fire("Error", "Invalid server response. Please try again.", "error");
                return;
              }

              if (data.status === "success") {
                Toastify({
                  text: data.message,
                  duration: 3000,
                  close: true,
                  gravity: "top",
                  position: "right",
                  style: { background: "linear-gradient(to right, #000, #d008af)" }
                }).showToast();

                if (queueItem) {
                  queueItem.remove();
                  updateGridLayout(); // Ensure the layout updates properly
                  handleQueueShift(); // Move the next queue item if needed
                }
              } else {
                Swal.fire("Error", data.message, "error");
              }
            })
            .catch(error => {
              console.error("Delete Error:", error);
              Swal.fire("Error", "An error occurred. Please try again.", "error");
            });
        }
      });
    });

    function handleQueueShift() {
      let leftContainer = document.getElementById("queue-container");
      let rightList = document.getElementById("queueList");

      if (leftContainer.children.length < 20) {
        let firstRightItem = rightList.querySelector("li");
        if (firstRightItem) {
          firstRightItem.remove(); // Remove from the right section
          leftContainer.appendChild(firstRightItem); // Move to the left section
          updateGridLayout();
        }
      }
    }

    function updateGridLayout() {
      const queueContainer = document.getElementById("queue-container");
      const queueList = document.getElementById("queueList");

      if (!queueContainer || !queueList) {
        console.warn("updateGridLayout: Queue elements not found.");
        return;
      }

      const leftItems = queueContainer.children.length;
      const rightItems = queueList.children.length;

      queueContainer.style.gridTemplateColumns = `repeat(${leftItems < 5 ? leftItems : 5}, 1fr)`;
      queueList.style.gridTemplateColumns = `repeat(${rightItems < 5 ? rightItems : 5}, 1fr)`;
    }
  </script>
</body>

</html>