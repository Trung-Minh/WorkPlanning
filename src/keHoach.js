// Xử lí khi màn hình nhỏ thì scale
const menuToggle = document.getElementById("menuToggle");
const navMenu = document.getElementById("navMenu");

menuToggle.addEventListener("click", () => {
  navMenu.classList.toggle("hidden");
});

function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  sidebar.classList.toggle("hidden");
}

let sectionCount = 0;
let currentSection = "";
let activeButton = null;
let currentFormWrapper = null;
let templateIndex = 3;
const counters = {};

const templates = {
  mau1: [
    "Tồn Động",
    "Chuẩn Bị Nội Dung",
    "Đã chờ xem xét",
    "Cần Xuất Bản",
    "Đã xuất bản",
  ],
  mau2: ["Ý tưởng", "Giao diện", "Phác thảo", "Sản phẩm cuối", "Xuất bản"],
};

function loadTemplate(name, el = null) {
  document.getElementById("templateContent").innerHTML = "";
  document.getElementById("pageTitle").textContent =
    localStorage.getItem(name + "_title") || `Mẫu ${name.replace("mau", "")}`;
  sectionCount = 0;
  Object.keys(counters).forEach((key) => delete counters[key]);
  (templates[name] || []).forEach((title) => {
    const id = `section_${sectionCount++}`;
    counters[id] = 0;
    createColumn(id, title);
  });
  document
    .querySelectorAll("#menuList a")
    .forEach((a) => a.classList.remove("active-tab"));
  if (el) el.classList.add("active-tab");
  makeEditableTitle(el, name, true);
}

window.onload = () => {
  loadTemplate("mau1", document.querySelector('[data-id="mau1"]'));
};

function createNewTemplate() {
  const key = `mau${templateIndex++}`;
  templates[key] = [];
  const li = document.createElement("li");
  const a = document.createElement("a");
  a.href = "#";
  a.className = "block px-3 py-2 rounded hover:bg-blue-200 editable-tab";
  a.textContent = `Mẫu ${key.replace("mau", "")}`;
  a.dataset.id = key;
  a.onclick = function () {
    loadTemplate(key, this);
  };
  makeEditableTitle(a, key, true);
  li.appendChild(a);
  document.getElementById("menuList").appendChild(li);
}

function makeEditableTitle(titleElement, key, isTemplate = false) {
  let isEditing = false;
  titleElement.addEventListener("dblclick", () => {
    if (isEditing) return;
    isEditing = true;
    const currentText = titleElement.textContent;
    const input = document.createElement("input");
    input.type = "text";
    input.value = currentText;
    input.className = "border p-1 text-sm w-full";
    input.onblur = () => {
      const newTitle = input.value.trim() || currentText;
      const newElem = document.createElement(
        titleElement.tagName.toLowerCase()
      );
      newElem.textContent = newTitle;
      newElem.className = titleElement.className;
      newElem.dataset.id = titleElement.dataset.id;
      newElem.onclick = titleElement.onclick;
      makeEditableTitle(newElem, key, isTemplate);
      localStorage.setItem(key + "_title", newTitle);
      input.replaceWith(newElem);
    };
    titleElement.replaceWith(input);
    input.focus();
  });
}

function createColumn(id, label) {
  const wrapper = document.createElement("div");
  wrapper.className = "gradient-box rounded p-3";

  const title = document.createElement("div");
  title.className = "font-bold mb-3 flex justify-between items-center text-lg";
  const span = document.createElement("span");
  span.textContent = label;
  span.className = "editable-title cursor-pointer";
  makeEditableTitle(span, id);
  title.appendChild(span);

  const delBtn = document.createElement("button");
  delBtn.textContent = "✕";
  delBtn.className = "text-red-500 text-xs ml-2";
  delBtn.onclick = () => wrapper.remove();
  title.appendChild(delBtn);

  const formContainer = document.createElement("div");
  formContainer.id = id;
  formContainer.className = "space-y-2 flex-1";

  const addBtn = document.createElement("button");
  addBtn.className = "text-blue-600 hover:underline text-sm mt-2";
  addBtn.textContent = "+ Thêm mục";
  addBtn.onclick = () => addForm(id);

  wrapper.appendChild(title);
  wrapper.appendChild(formContainer);
  wrapper.appendChild(addBtn);
  document.getElementById("templateContent").appendChild(wrapper);
}

function addNewColumn() {
  const id = `section_${sectionCount++}`;
  counters[id] = 0;
  createColumn(id, `Tiêu đề ${sectionCount}`);
}

function addForm(containerId) {
  counters[containerId]++;
  const index = counters[containerId];
  const container = document.getElementById(containerId);

  const wrapper = document.createElement("div");
  wrapper.className = "flex flex-col gap-1 relative p-1 rounded";
  wrapper.style.backgroundColor = getRandomPastelColor();

  const formId = `${containerId}_form_${index}`;
  const title = document.createElement("span");
  title.textContent = localStorage.getItem(`${formId}_title`) || `Mục ${index}`;
  title.className = "block font-medium text-left cursor-pointer";
  makeEditableTitle(title, formId);

  const contentBtn = document.createElement("button");
  contentBtn.textContent = localStorage.getItem(`${formId}_deadline`)
    ? `⏰ ${new Date(localStorage.getItem(`${formId}_deadline`)).toLocaleString(
        "vi-VN"
      )}`
    : "✎ Nhập nội dung";
  contentBtn.className = "text-sm text-blue-600 underline";
  contentBtn.onclick = () => openForm(formId, contentBtn, wrapper);

  wrapper.appendChild(title);
  wrapper.appendChild(contentBtn);
  container.appendChild(wrapper);
}

function openForm(formId, button = null, wrapper = null) {
  currentSection = formId;
  activeButton = button;
  currentFormWrapper = wrapper;
  document.getElementById("formTitle").textContent =
    localStorage.getItem(formId + "_title") || formId;
  document.getElementById("formTextarea").value =
    localStorage.getItem(formId + "_content") || "";
  document.getElementById("formDeadline").value =
    localStorage.getItem(formId + "_deadline") || "";
  document.getElementById("formModal").classList.remove("hidden");
  document.getElementById("formModal").classList.add("flex");
}

function closeForm() {
  document.getElementById("formModal").classList.add("hidden");
}

function saveForm() {
  const content = document.getElementById("formTextarea").value;
  const deadline = document.getElementById("formDeadline").value;
  localStorage.setItem(currentSection + "_content", content);
  localStorage.setItem(currentSection + "_deadline", deadline);
  if (activeButton) {
    activeButton.textContent = deadline
      ? `⏰ ${new Date(deadline).toLocaleString("vi-VN")}`
      : "✎ Nhập nội dung";
  }
  closeForm();
}

function deleteCurrentForm() {
  if (currentFormWrapper && confirm("Bạn có chắc muốn xóa mục này?")) {
    currentFormWrapper.remove();
    closeForm();
  }
}

function clearDeadline() {
  document.getElementById("formDeadline").value = "";
}

function getRandomPastelColor() {
  const hue = Math.floor(Math.random() * 360);
  return `hsl(${hue}, 70%, 90%)`;
}
