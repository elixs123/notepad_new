@extends('default')

@section('content')
<div class="container p-0" style="height: 100vh;">
    <div class="notepad-container d-flex flex-column" style="height: 100%;">

        <!-- HEADER -->
        <div class="notepad-head p-2 bg-light">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div class="notepad-logo d-flex align-items-center mb-2 mb-md-0">
                    <img src="{{ asset('img/icon.png') }}" alt="icon" style="height: 40px;">
                    <h4 class="notepad-title ms-2 m-0">Notepad</h4>
                </div>
                <div class="d-flex flex-wrap">
                    <button id="speechBtn" class="btn btn-secondary m-1 btn-sm" data-bs-toggle="tooltip" title="Microphone"><i class="fa-solid fa-microphone"></i></button>
                    <livewire:favorites :note-id="$notes->id" />

                    <livewire:note-password :note="$notes" />
                    <livewire:edit-url :note="$notes" />
                    <a href="{{ route('home') }}" class="btn btn-secondary m-1 btn-sm" data-bs-toggle="tooltip" title="New note"><i class="fa-solid fa-plus"></i></a>
                    <form action="{{ route('upload.file') }}" method="POST" enctype="multipart/form-data" class="d-inline-block">
                        @csrf
                        <label for="fileUpload" class="btn btn-secondary m-1 btn-sm" data-bs-toggle="tooltip" title="Upload document" style="cursor:pointer;">
                            <i class="fa-solid fa-upload"></i>
                        </label>
                        <input type="hidden" name="note_url" value="{{ $notes->url   }}">
                        <input id="fileUpload" type="file" name="file" class="d-none" onchange="this.form.submit()">
                    </form>
                    <button id="darkModeToggle" class="btn btn-secondary m-1 btn-sm" data-bs-toggle="tooltip" title="Dark mode">
                        <i class="fa-solid fa-toggle-on"></i>
                    </button>

                </div>
            </div>
        </div>

        <div class="notepad-body flex-grow-1">
            <!-- Toolbar -->
            <div id="toolbar-container">
                <span class="ql-formats">
                    <select class="ql-font"></select>
                    <select class="ql-size"></select>
                </span>
                <span class="ql-formats">
                    <button class="ql-bold"></button>
                    <button class="ql-italic"></button>
                    <button class="ql-underline"></button>
                    <button class="ql-strike"></button>
                </span>
                <span class="ql-formats">
                    <select class="ql-color"></select>
                    <select class="ql-background"></select>
                </span>
                <span class="ql-formats">
                    <button class="ql-script" value="sub"></button>
                    <button class="ql-script" value="super"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-header" value="1"></button>
                    <button class="ql-header" value="2"></button>
                    <button class="ql-blockquote"></button>
                    <button class="ql-code-block"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-list" value="ordered"></button>
                    <button class="ql-list" value="bullet"></button>
                    <button class="ql-indent" value="-1"></button>
                    <button class="ql-indent" value="+1"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-direction" value="rtl"></button>
                    <select class="ql-align"></select>
                </span>
                <span class="ql-formats">
                    <button class="ql-link"></button>
                    <button class="ql-image"></button>
                    <button class="ql-video"></button>
                    <button class="ql-formula"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-clean"></button>
                </span>
            </div>
            <div id="editor" style="height: 93%; width: 100%;"></div>
        </div>

        <!-- FOOTER -->
        <div class="notepad-footer border-top d-flex flex-column align-items-center text-center">
            <div class="container">
                <p id="wordCount">Words: 0, Characters: 0</p>
            </div>
            <div>
                <p>Created at: August 17, 2025, 11:27 am</p>
            </div>
            <div>
                <p>Last modified: August 17, 2025, 11:27 am</p>
            </div>
            <div class="d-flex flex-wrap justify-content-center">
                <button class="btn btn-secondary m-1 btn-sm" onclick="copyToClipboard(getEditableUrl())">
                    <i class="fa-solid fa-copy"></i> Copy Editable URL
                </button>
                <button class="btn btn-secondary m-1 btn-sm" onclick="copyToClipboard(getShareUrl())">
                    <i class="fa-solid fa-share"></i> Copy Share URL
                </button>
                <button class="btn btn-secondary m-1 btn-sm" onclick="openView('raw')">
                    <i class="fa-solid fa-file"></i> Raw View
                </button>
                <button class="btn btn-secondary m-1 btn-sm" onclick="openView('markdown')">
                    <i class="fa-brands fa-markdown"></i> Markdown View
                </button>
                <button class="btn btn-secondary m-1 btn-sm" onclick="openView('code')">
                    <i class="fa-solid fa-code"></i> Code View
                </button>
            </div>

            <div class="d-flex flex-wrap justify-content-center">
                <button class="btn btn-secondary m-1 btn-sm">
                    <i class="fa-brands fa-facebook"></i> Share to Facebook
                </button>
                <button class="btn btn-secondary m-1 btn-sm">
                    <i class="fa-brands fa-whatsapp"></i> Share to WhatsApp
                </button>
                <button class="btn btn-secondary m-1 btn-sm">
                    <i class="fa-brands fa-reddit"></i> Share to Reddit
                </button>
                <button class="btn btn-secondary m-1 btn-sm">
                    <i class="fa-brands fa-telegram"></i> Share to Telegram
                </button>
                <button class="btn btn-secondary m-1 btn-sm">
                    <i class="fa-brands fa-twitter"></i> Share to Twitter
                </button>
                <button class="btn btn-secondary m-1 btn-sm">
                    <i class="fa-solid fa-envelope"></i> Share to Email
                </button>
            </div>

        </div>

    </div>
</div>

<script>
  var quill = new Quill('#editor', {
      theme: 'snow',
      modules: {
          toolbar: '#toolbar-container' 
      }
  });

    
  var delta;

try {
    delta = JSON.parse(@json($notes->content ?? "{}"));
    quill.setContents(delta);
} catch (e) {
    quill.setText(@json($notes->content ?? ""));
}

  let saveTimer;

  function updateStats() {
      let text = quill.getText().trim();
      let words = text.length > 0 ? text.split(/\s+/).length : 0;
      let characters = text.length;
      let sentences = text.length > 0 ? (text.match(/[.!?]+/g) || []).length : 0;

      document.getElementById("wordCount").innerText =
          "Words: " + words + ", Characters: " + characters + ", Sentences: " + sentences;
  }

  quill.on('text-change', function() {
      updateStats();

      clearTimeout(saveTimer);
      saveTimer = setTimeout(() => {
          let content = JSON.stringify(quill.getContents());

          fetch("{{ route('notes.update', $notes->url) }}", {
              method: "POST",
              headers: {
                  "Content-Type": "application/json",
                  "X-CSRF-TOKEN": "{{ csrf_token() }}"
              },
              body: JSON.stringify({ content: content })
          })
          .then(res => console.log(res))
          .then(data => console.log("Autosaved", data))
          .catch(err => console.error("Error saving:", err));
      }, 5000); // 5 sekundi
  });

  //speach
  const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (SpeechRecognition) {
        const recognition = new SpeechRecognition();
        recognition.continuous = true; 
        recognition.interimResults = true; 
        recognition.lang = 'en-US'; 

        let recognizing = false;

        document.getElementById('speechBtn').addEventListener('click', () => {
            if (!recognizing) {
                recognition.start();
            } else {
                recognition.stop();
            }
        });

        recognition.onstart = () => {
            recognizing = true;
            document.getElementById('speechBtn').classList.add('btn-success');
        };

        recognition.onend = () => {
            recognizing = false;
            document.getElementById('speechBtn').classList.remove('btn-success');
        };

        recognition.onresult = (event) => {
            let transcript = '';
            for (let i = event.resultIndex; i < event.results.length; ++i) {
                transcript += event.results[i][0].transcript;
            }

            const range = quill.getSelection();
            const position = range ? range.index : quill.getLength();
            quill.insertText(position, transcript);
            quill.setSelection(position + transcript.length);
            updateStats();
        };
    } else {
        alert('Your browser does not support Speech Recognition.');
    }

</script>
<script>
    const noteUrl = "{{ $notes->url }}";

    function getBaseUrl() {
        return window.location.origin;
    }

    function getEditableUrl() {
        return `${getBaseUrl()}/${noteUrl}`;
    }

    function getShareUrl() {
        return `${getBaseUrl()}/share`;
    }

    function openView(type) {
        const baseUrl = getBaseUrl();
        let url = "";
        switch(type) {
            case 'raw':
                url = `${baseUrl}/raw`;
                break;
            case 'markdown':
                url = `${baseUrl}/markdown`;
                break;
            case 'code':
                url = `${baseUrl}/code`;
                break;
        }
        window.open(url, "_blank");
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text)
            .then(() => {
                console.log("Copied to clipboard:", text);
            })
            .catch(err => console.error("Failed to copy:", err));
    }
</script>

@endsection
