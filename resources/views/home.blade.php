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

<!-- ads -->
<div class="monetumo-ad-slot ad-leaderboard_atf"></div>
<div class="monetumo-ad-slot ad-leaderboard_btf"></div>
<!-- ./ads -->
 
    <div class="py-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="homepage-about">
                    <h2>Online Notepad that you can share!</h2>
                    <p>Notepad.link allows users to easily save notes online. The notes are saved to your browser, which you can view and modify as often as you like. It can be used as an online journal. You can edit your notes and even share them. Access to Notepad.link from any location is completely free.</p>
                    <p>You can use this online diary anonymously and save notes. You can also access your notes at any time by coming back to it anytime. You can either leave your notes open and available, or you can lock your notes with a password. You can easily share your notes via Twitter, Facebook, Whatsapp &amp; Reddit.</p>
                    <p>Notepad.link removes any formatting from text pasted into it. Instead of html text, you will get the plain text. You cannot use any special font, but you can switch between our normal font and Monospace.</p>
                    <p>Notepad.link can also be used to quickly store a copy of any text. You can quickly open Notepad, save a copy of your content to it, and then save the URL in your bookmarks. This way, if anything goes wrong, you can always have a backup copy. Notepad is faster than Word and requires far less system resources.</p>
                    <p>Unless you make changes, Notepad.link does not make any changes to the files it opens. This makes it useful for editing and examining files that may have been damaged by other programs.</p>
                    <p>Our Web server recognizes each visitor to the notepad website page automatically. However, it does not recognize the visitor’s e-mail address. This makes it more secure.</p>


                    <h2>Share Notepad Link</h2>
                    <p>Notepad.link allows you to create customized and personalized notepad links. You can edit the url after the slash (/) and create any notepad name. You can then share this link with anyone that you can want to give access to your notes. Please note: anyone that has access to your notepad link, can edit the notepad. If you dont want to allow others to be able to edit your notes, please make sure that you click on the shareable link button in the bottom of your notepad, and rather share this url with other users. That way your notes will always be safe.</p>
                    <p>Create a customizable and personalized notepad link that you can share with anyone. Collaborate and edit notes together in real-time, making teamwork a breeze. This feature is perfect for brainstorming sessions, planning projects, or sharing ideas with friends and colleagues.</p>


                    <h2>Share Safe Notepad Link</h2>
                    <p>Generate a read-only link for your notepad to ensure others cannot edit your notes, maintaining your content's integrity. This feature is ideal for sharing important documents, notes, or ideas without risking accidental changes or unauthorized edits.</p>


                    <h2>Customizable URL</h2>
                    <p>Personalize your notepad link for easy recall and better organization. By customizing your notepad's URL, you can create memorable links that are easily recognizable, making it simpler to find and access your notes when you need them.</p>


                    <h2>Google Chrome Extension Plugin</h2>
                    <p>Keep all your favorite notes saved in your browser for quick and convenient access. With the Google Chrome Extension Plugin, you can easily save and organize your notes, so they're always just a click away.</p>


                    <h2>Upload .txt Files</h2>
                    <p>Turn your existing text files into shareable notepads, making it simple to collaborate and share your content. Import your existing notes or documents and easily convert them into an online notepad, streamlining your workflow and making collaboration a breeze.</p>


                    <h2>Light/Dark Mode</h2>
                    <p>Switch between light and dark themes to suit your preference and reduce eye strain. Choose the best theme for your working environment and enjoy a more comfortable and personalized note-taking experience.</p>


                    <h2>Add and Receive Comments</h2>
                    <p>Receive valuable feedback and engage in discussions with other users and colleagues using the comment section on shared notes. This feature enables you to gather opinions, suggestions, and feedback from others, helping you refine your ideas and improve your work.</p>


                    <h2>Password-Protected Notepads</h2>
                    <p>Secure your private notes with password protection for an added layer of privacy. Safeguard sensitive information and ensure that only authorized users can access your most important notes.</p>


                    <h2>Shareable Links for Raw Text, Markdown Text, and Code</h2>
                    <p>Share your notepad in different formats to suit the needs of your collaborators. Whether you're working with plain text, markdown, or code, Notepad.link allows you to easily share your content in the most appropriate format for your audience.</p>


                    <h2>Word and Character Counter</h2>
                    <p>Keep track of your writing progress and meet specific word count requirements with ease. With the built-in word and character counter, you can monitor your writing and ensure your content stays within the desired length.</p>


                    <h2>Search Other Notes</h2>
                    <p>Discover and explore notes created by other users for inspiration and learning. Browse through a wide variety of content and gain new insights, ideas, and perspectives from other users' notes.</p>


                    <h2>Anonymous and Secure</h2>
                    <p>Use Notepad.link without revealing your email address, ensuring your privacy and security. By not collecting user email addresses, Notepad.link provides a secure and anonymous platform for your note-taking needs, so you can focus on your work without worrying about your personal information.</p>


                    <h2>Collaborative Editing</h2>
                    <p>Work together with your team or friends on the same notepad in real-time. The collaborative editing feature allows multiple users to edit the same notepad simultaneously, promoting efficient teamwork and seamless idea sharing.</p>


                    <h2>Text Formatting Options</h2>
                    <p>Strip any formatting from text pasted into Notepad.link, resulting in clean and unformatted text. While special fonts are not supported, you can switch between the default font and Monospace, tailoring the notepad to your preferences.</p>


                    <h2>Quick Backup Solution</h2>
                    <p>Use Notepad.link as a fast and efficient way to backup your text content. Save a copy of your important text in a notepad, and store the URL in your bookmarks. This way, you always have a backup copy of your content, even if your primary source encounters issues.</p>


                    <h2>File Examination and Editing</h2>
                    <p>Notepad.link allows you to examine and edit files without making any changes unless you explicitly do so. This feature makes it an ideal tool for editing and examining files that may have been damaged or corrupted by other programs.</p>


                    <h2>Accessible from Anywhere</h2>
                    <p>Access your notes from any location, free of charge. Notepad.link's online platform allows you to store and manage your notes, making them available to you whenever you need them. Easily retrieve your notes, whether you're on your personal computer, at work, or using a public device.</p>


                    <h2>Compatible with Multiple Sharing Platforms</h2>
                    <p>Effortlessly share your notes via various platforms such as Twitter, Facebook, WhatsApp, and Reddit. Notepad.link's compatibility with multiple sharing platforms makes it simple to distribute your notes, ideas, and content with others.</p>


                    <h2>Boost Productivity</h2>
                    <p>Save time and energy by consolidating all your notes in one place. Notepad.link's online platform and Chrome Extension Plugin help improve productivity by providing a convenient and organized space for all your note-taking needs.</p>


                    <h2>Ideal for Various Writing Projects</h2>
                    <p>Whether you're writing articles, essays, reports, or any other text-based content, Notepad.link's word count feature ensures that your content meets specified requirements or stays within set limits. This feature is particularly helpful for students, professionals, and writers who need to adhere to specific word counts.</p>


                    <p>By offering a wide range of features, Notepad.link aims to provide a comprehensive online notepad solution to meet all your note-taking, sharing, and collaboration needs.</p>


                    <p><strong>Disclaimer:</strong> While we strive to make our tools as accurate and reliable as possible, it is not always possible.</p>
                </div>
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
