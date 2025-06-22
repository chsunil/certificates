// File: js/send-email.js

document.addEventListener('DOMContentLoaded', function() {
  'use strict';

  // CACHE DOM ELEMENTS
  const modalEl    = document.getElementById('sendEmailModal');
  const formEl     = document.getElementById('sendEmailForm');
  const toEmailEl  = modalEl.querySelector('#toEmail');
  const subjectEl  = modalEl.querySelector('#subject');
  const messageEl  = modalEl.querySelector('#message');
  const pdfInputEl = modalEl.querySelector('#pdfAttachment');
  const filenameEl = modalEl.querySelector('#pdfFilename');
  const clientNameEl = modalEl.querySelector('#clientname');

  // 1) Populate modal fields when it is shown
  modalEl.addEventListener('show.bs.modal', function(event) {

    const btn       = event.relatedTarget;
    const client    = btn.getAttribute('data-client-name')      || 'name';
    const email     = btn.getAttribute('data-email')            || 'email';
    const pdfUrl    = btn.getAttribute('data-pdf-url')          || 'url';
    const pdfFile   = btn.getAttribute('data-pdf-filename')     || 'file';

    clientNameEl.textContent  = client;
    toEmailEl.value           = email;
    pdfInputEl.value          = pdfUrl;
    filenameEl.textContent    = pdfFile;

    // Optional: initialize Froala if loaded
    if (typeof FroalaEditor !== 'undefined') {
      new FroalaEditor('#message', {
        theme: 'royal',
        height: 250,
        toolbarButtons: [
          ['bold','italic','underline','strikeThrough','subscript','superscript'],
          ['fontFamily','fontSize','textColor','backgroundColor'],
          ['inlineClass','inlineStyle','clearFormatting']
        ]
      });
    }
  });

  // 2) Handle form submission via AJAX
  formEl.addEventListener('submit', function(e) {
    e.preventDefault();

    const params = new URLSearchParams({
      action:         'send_pdf_email',
      to_email:       toEmailEl.value,
      subject:        subjectEl.value,
      message:        messageEl.value,
      pdf_attachment: pdfInputEl.value,
      nonce:          wp_vars.send_pdf_email_nonce
    });

    fetch(wp_vars.ajax_url, {
      method:  'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body:    params.toString()
    })
    .then(response => response.json())
    .then(json => {
      if (json.success) {
        // Hide the modal (Bootstrap cleans up backdrop for you)
        const bsModal = bootstrap.Modal.getInstance(modalEl) 
                     || new bootstrap.Modal(modalEl);
        bsModal.hide();
        alert('Email sent successfully!');
      } else {
        const err = (json.data && json.data.message) || json.message || 'Unknown error';
        alert('Error sending email: ' + err);
      }
    })
    .catch(err => {
      console.error('Error sending email:', err);
      alert('An error occurred while sending the email.');
    });
  });
});
