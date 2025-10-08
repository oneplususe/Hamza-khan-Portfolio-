// Ye Netlify Function hai jo form data ko Telegram par bhejega

exports.handler = async (event, context) => {
  // Form se aaye data ko parse karte hain
  const { name, email, message } = JSON.parse(event.body);

  // Environment variables se token aur ID le rahe hain
  // Ye values `netlify.toml` se aayengi
  const botToken = process.env.TELEGRAM_BOT_TOKEN;
  const chatId = process.env.TELEGRAM_CHAT_ID;

  // Agar token ya ID missing hai toh error denge
  if (!botToken || !chatId) {
    return {
      statusCode: 500,
      body: JSON.stringify({ status: 'error', message: 'Server configuration error.' }),
    };
  }

  // Telegram ke liye message format banate hain
  const text = `<b>New Message from Portfolio!</b>\n\n<b>Name:</b> ${name}\n<b>Email:</b> ${email}\n<b>Message:</b>\n${message}`;

  try {
    // Telegram API ko call kar rahe hain
    const response = await fetch(`https://api.telegram.org/bot${botToken}/sendMessage`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        chat_id: chatId,
        text: text,
        parse_mode: 'HTML'
      })
    });

    const data = await response.json();

    // Agar API call successful hai toh success response denge
    if (data.ok) {
      return {
        statusCode: 200,
        body: JSON.stringify({ status: 'success' }),
      };
    } else {
      // Agar Telegram se error aaye toh wo error denge
      return {
        statusCode: 400,
        body: JSON.stringify({ status: 'error', message: data.description }),
      };
    }
  } catch (error) {
    // Agar koi network ya server error aaye toh
    return {
      statusCode: 500,
      body: JSON.stringify({ status: 'error', message: 'Failed to send message.' }),
    };
  }
};
